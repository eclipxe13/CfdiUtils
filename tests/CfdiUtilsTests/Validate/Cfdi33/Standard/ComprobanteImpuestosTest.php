<?php
namespace CfdiUtilsTests\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\Node;
use CfdiUtils\Validate\Cfdi33\Standard\ComprobanteImpuestos;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\Validate\ValidateTestCase;

class ComprobanteImpuestosTest extends ValidateTestCase
{
    /** @var  ComprobanteImpuestos */
    protected $validator;

    protected function setUp()
    {
        parent::setUp();
        $this->validator = new ComprobanteImpuestos();
    }

    public function providerValidImpuestos()
    {
        return [
            [true, false],
            [false, true],
            [true, true],
        ];
    }

    /**
     * @param bool $putTraslados
     * @param bool $putRetenciones
     * @dataProvider providerValidImpuestos
     */
    public function testValidImpuestos($putTraslados, $putRetenciones)
    {
        $nodeImpuestos = new Node('cfdi:Impuestos');
        if ($putTraslados) {
            $nodeImpuestos['TotalImpuestosTrasladados'] = '';
            $nodeImpuestos->addChild(new Node('cfdi:Traslados', [], [new Node('cfdi:Traslado')]));
        }
        if ($putRetenciones) {
            $nodeImpuestos['TotalImpuestosRetenidos'] = '';
            $nodeImpuestos->addChild(new Node('cfdi:Retenciones', [], [new Node('cfdi:Retencion')]));
        }
        $this->comprobante->addChild($nodeImpuestos);

        $this->runValidate();
        $this->assertStatusEqualsCode(Status::ok(), 'COMPIMPUESTOSC01');
        $this->assertStatusEqualsCode(Status::ok(), 'COMPIMPUESTOSC02');
        $this->assertStatusEqualsCode(Status::ok(), 'COMPIMPUESTOSC03');
    }

    public function testInvalidWithEmptyImpuestos()
    {
        $this->comprobante->addChild(new Node('cfdi:Impuestos'));

        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'COMPIMPUESTOSC01');
    }

    public function testInvalidTrasladosNodesWithoutTotalTraslados()
    {
        $this->comprobante->addChild(new Node(
            'cfdi:Impuestos',
            [],
            [new Node('cfdi:Traslados', [], [new Node('cfdi:Traslado')])]
        ));

        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'COMPIMPUESTOSC02');
    }

    public function testInvalidTotalTrasladosWithoutTrasladosNodes()
    {
        $this->comprobante->addChild(new Node(
            'cfdi:Impuestos',
            ['TotalImpuestosTrasladados' => '']
        ));

        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'COMPIMPUESTOSC02');
    }

    public function testInvalidRetencionesNodesWithoutTotalRetenciones()
    {
        $this->comprobante->addChild(new Node(
            'cfdi:Impuestos',
            [],
            [new Node('cfdi:Retenciones', [], [new Node('cfdi:Retencion')])]
        ));

        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'COMPIMPUESTOSC03');
    }

    public function testInvalidTotalRetencionesWithoutRetencionesNodes()
    {
        $this->comprobante->addChild(new Node(
            'cfdi:Impuestos',
            ['TotalImpuestosRetenidos' => '']
        ));

        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'COMPIMPUESTOSC03');
    }

    public function testWithoutNodeImpuestos()
    {
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::none(), 'COMPIMPUESTOSC01');
        $this->assertStatusEqualsCode(Status::none(), 'COMPIMPUESTOSC02');
        $this->assertStatusEqualsCode(Status::none(), 'COMPIMPUESTOSC03');
    }
}
