<?php

namespace CfdiUtilsTests\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\Node;
use CfdiUtils\Validate\Cfdi33\Standard\ComprobanteDecimalesMoneda;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\Validate\ValidateTestCase;

final class ComprobanteDecimalesMonedaTest extends ValidateTestCase
{
    /** @var ComprobanteDecimalesMoneda */
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new ComprobanteDecimalesMoneda();
    }

    public function testUnknownCurrency()
    {
        $this->comprobante['Moneda'] = 'LYD'; // Dinar libio
        $this->runValidate();
        foreach ($this->asserts as $assert) {
            $this->assertStatusEqualsStatus(Status::none(), $assert->getStatus());
        }
    }

    public function testAllAssertsAreOk()
    {
        $this->comprobante->addAttributes([
            'Moneda' => 'MXN',
            'SubTotal' => '123',
            'Descuento' => '1.2',
            'Total' => '999.99',
        ]);
        $this->comprobante->addChild(new Node('cfdi:Impuestos', [
            'TotalImpuestosTrasladados' => '1.23',
            'TotalImpuestosRetenidos' => '1.23',
        ], [
            new Node('cfdi:Traslados', [], [
                new Node('cfdi:Traslado', ['Importe' => '123.45']),
            ]),
            new Node('cfdi:Retenciones', [], [
                new Node('cfdi:Retencion', ['Importe' => '123.45']),
            ]),
        ]));

        $this->runValidate();
        foreach ($this->asserts as $assert) {
            $this->assertStatusEqualsAssert(Status::ok(), $assert);
        }
    }

    public function testAllAssertsMissingAttributes()
    {
        $this->comprobante->addAttributes([
            'Moneda' => 'MXN',
        ]);
        $this->comprobante->addChild(new Node('cfdi:Impuestos', [
        ], [
            new Node('cfdi:Traslados', [], [
                new Node('cfdi:Traslado'),
            ]),
            new Node('cfdi:Retenciones', [], [
                new Node('cfdi:Retencion'),
            ]),
        ]));

        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'MONDEC01');
        $this->assertStatusEqualsCode(Status::ok(), 'MONDEC02');
        $this->assertStatusEqualsCode(Status::error(), 'MONDEC03');
        $this->assertStatusEqualsCode(Status::ok(), 'MONDEC04');
        $this->assertStatusEqualsCode(Status::ok(), 'MONDEC05');
    }

    public function testAllAssertAreError()
    {
        $this->comprobante->addAttributes([
            'Moneda' => 'MXN',
            'SubTotal' => '123.000',
            'Descuento' => '123.000',
            'Total' => '123.000',
        ]);
        $this->comprobante->addChild(new Node('cfdi:Impuestos', [
            'TotalImpuestosTrasladados' => '123.000',
            'TotalImpuestosRetenidos' => '123.000',
        ], [
            new Node('cfdi:Traslados', [], [
                new Node('cfdi:Traslado', ['Importe' => '123.00']),
                new Node('cfdi:Traslado', ['Importe' => '123.000']),
            ]),
            new Node('cfdi:Retenciones', [], [
                new Node('cfdi:Retencion', ['Importe' => '123.00']),
                new Node('cfdi:Retencion', ['Importe' => '123.000']),
            ]),
        ]));

        $this->runValidate();
        foreach ($this->asserts as $assert) {
            $this->assertStatusEqualsAssert(Status::error(), $assert);
        }
    }
}
