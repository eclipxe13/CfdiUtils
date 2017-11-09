<?php
namespace CfdiUtilsTests\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\Node;
use CfdiUtils\Validate\Cfdi33\Standard\ConceptoImpuestos;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\Validate\ValidateTestCase;

class ConceptoImpuestosTest extends ValidateTestCase
{
    /** @var ConceptoImpuestos */
    protected $validator;

    protected function setUp()
    {
        parent::setUp();
        $this->validator = new ConceptoImpuestos();
    }

    public function testValidCaseNoRetencionOrTraslado()
    {
        $this->comprobante->addChild(
            new Node('cfdi:Conceptos', [], [
                new Node('cfdi:Concepto'),
                new Node('cfdi:Concepto', [], [
                    new Node('cfdi:Impuestos', [], [
                        new Node('cfdi:Traslados', [], [
                            new Node('cfdi:Traslado', ['Base' => '1']),
                        ]),
                        new Node('cfdi:Retenciones', [], [
                            new Node('cfdi:Retencion'),
                        ]),
                    ]),
                ]),
            ])
        );
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::ok(), 'CONCEPIMPC01');
    }

    public function testInvalidCaseNoRetencionOrTraslado()
    {
        $this->comprobante->addChild(
            new Node('cfdi:Conceptos', [], [
                new Node('cfdi:Concepto'),
                new Node('cfdi:Concepto', [], [
                    new Node('cfdi:Impuestos', [], [
                        new Node('cfdi:Traslados', [], []),
                        new Node('cfdi:Retenciones', [], []),
                    ]),
                ]),
            ])
        );
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'CONCEPIMPC01');
    }

    public function testTrasladosValidCase()
    {
        $this->comprobante->addChild(
            new Node('cfdi:Conceptos', [], [
                new Node('cfdi:Concepto'),
                new Node('cfdi:Concepto', [], [
                    new Node('cfdi:Impuestos', [], [
                        new Node('cfdi:Traslados', [], [
                            new Node('cfdi:Traslado', ['Base' => '0.000001']),
                        ]),
                    ]),
                ]),
                new Node('cfdi:Concepto', [], [
                    new Node('cfdi:Impuestos', [], [
                        new Node('cfdi:Traslados', [], [
                            new Node('cfdi:Traslado', ['Base' => '1']),
                        ]),
                    ]),
                ]),
            ])
        );
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::ok(), 'CONCEPIMPC02');
    }

    public function providerInvalidTraslado()
    {
        return[
            ['0'],
            ['0.0000001'],
            ['-1'],
            ['foo'],
            ['0.0.0.0'],
        ];
    }

    /**
     * @param $base
     * @dataProvider providerInvalidTraslado
     */
    public function testInvalidTraslado($base)
    {
        $this->comprobante->addChild(
            new Node('cfdi:Conceptos', [], [
                new Node('cfdi:Concepto'),
                new Node('cfdi:Concepto', [], [
                    new Node('cfdi:Impuestos', [], [
                        new Node('cfdi:Traslados', [], [
                            new Node('cfdi:Traslado', ['Base' => $base]),
                        ]),
                    ]),
                ]),
            ])
        );
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'CONCEPIMPC02');
    }
}
