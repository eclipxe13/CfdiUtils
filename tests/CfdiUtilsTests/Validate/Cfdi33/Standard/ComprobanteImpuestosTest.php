<?php

namespace CfdiUtilsTests\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\Node;
use CfdiUtils\Validate\Cfdi33\Standard\ComprobanteImpuestos;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\Validate\Validate33TestCase;

final class ComprobanteImpuestosTest extends Validate33TestCase
{
    /** @var  ComprobanteImpuestos */
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new ComprobanteImpuestos();
    }

    /**
     * @param bool $putTraslados
     * @param bool $putRetenciones
     * @testWith [true, false]
     *           [false, true]
     *           [true, true]
     */
    public function testValidImpuestos(bool $putTraslados, bool $putRetenciones): void
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

    public function testInvalidWithEmptyImpuestos(): void
    {
        $this->comprobante->addChild(new Node('cfdi:Impuestos'));

        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'COMPIMPUESTOSC01');
    }

    public function testInvalidTrasladosNodesWithoutTotalTraslados(): void
    {
        $this->comprobante->addChild(new Node(
            'cfdi:Impuestos',
            [],
            [new Node('cfdi:Traslados', [], [new Node('cfdi:Traslado')])]
        ));

        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'COMPIMPUESTOSC02');
    }

    public function testValidTotalTrasladosWithoutTrasladosNodes(): void
    {
        $this->comprobante->addChild(new Node(
            'cfdi:Impuestos',
            ['TotalImpuestosTrasladados' => '']
        ));

        $this->runValidate();
        $this->assertStatusEqualsCode(Status::ok(), 'COMPIMPUESTOSC02');
    }

    public function testInvalidRetencionesNodesWithoutTotalRetenciones(): void
    {
        $this->comprobante->addChild(new Node(
            'cfdi:Impuestos',
            [],
            [new Node('cfdi:Retenciones', [], [new Node('cfdi:Retencion')])]
        ));

        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'COMPIMPUESTOSC03');
    }

    public function testValidTotalRetencionesWithoutRetencionesNodes(): void
    {
        $this->comprobante->addChild(new Node(
            'cfdi:Impuestos',
            ['TotalImpuestosRetenidos' => '']
        ));

        $this->runValidate();
        $this->assertStatusEqualsCode(Status::ok(), 'COMPIMPUESTOSC03');
    }

    public function testWithoutNodeImpuestos(): void
    {
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::none(), 'COMPIMPUESTOSC01');
        $this->assertStatusEqualsCode(Status::none(), 'COMPIMPUESTOSC02');
        $this->assertStatusEqualsCode(Status::none(), 'COMPIMPUESTOSC03');
    }
}
