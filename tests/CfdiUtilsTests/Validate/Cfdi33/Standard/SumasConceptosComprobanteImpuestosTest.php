<?php

namespace CfdiUtilsTests\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\Node;
use CfdiUtils\Validate\Cfdi33\Standard\SumasConceptosComprobanteImpuestos;
use CfdiUtils\Validate\Contracts\DiscoverableCreateInterface;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\Validate\Validate33TestCase;

final class SumasConceptosComprobanteImpuestosTest extends Validate33TestCase
{
    /** @var SumasConceptosComprobanteImpuestos */
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new SumasConceptosComprobanteImpuestos();
    }

    public function testObjectSpecification(): void
    {
        $this->assertInstanceOf(DiscoverableCreateInterface::class, $this->validator);
        $this->assertTrue($this->validator->canValidateCfdiVersion('3.3'));
    }

    public function testValidateOk(): void
    {
        $this->setupCfdiFile('cfdi33-valid.xml');
        $this->runValidate();
        // all asserts
        foreach ($this->asserts as $assert) {
            $this->assertStatusEqualsAssert(Status::ok(), $assert);
        }
        // total expected count: 12 regular + 2 extras
        $this->assertCount(14, $this->asserts, 'All 14 expected asserts were are tested');
    }

    public function testValidateBadSubtotal(): void
    {
        $this->setupCfdiFile('cfdi33-valid.xml');
        $this->comprobante['SubTotal'] = '123.45';
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'SUMAS01');
    }

    public function testValidateUnsetSubtotal(): void
    {
        $this->setupCfdiFile('cfdi33-valid.xml');
        unset($this->comprobante['SubTotal']);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'SUMAS01');
    }

    public function testValidateBadDescuentos(): void
    {
        $this->setupCfdiFile('cfdi33-valid.xml');
        $this->comprobante['Descuento'] = '123.45';
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'SUMAS02');
    }

    public function testValidateBadTotal(): void
    {
        $this->setupCfdiFile('cfdi33-valid.xml');
        $this->comprobante['Total'] = '123.45';
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'SUMAS03');
    }

    public function testValidateUnsetTotal(): void
    {
        $this->setupCfdiFile('cfdi33-valid.xml');
        unset($this->comprobante['Total']);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'SUMAS03');
    }

    public function testValidateUnsetImpuestos(): void
    {
        $this->setupCfdiFile('cfdi33-valid.xml');
        $impuestos = $this->comprobante->searchNode('cfdi:Impuestos');
        if (null !== $impuestos) {
            $this->comprobante->children()->remove($impuestos);
        }
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'SUMAS04');
        $this->assertStatusEqualsCode(Status::error(), 'SUMAS05');
        $this->assertStatusEqualsCode(Status::error(), 'SUMAS06');
        $this->assertStatusEqualsCode(Status::error(), 'SUMAS08');
        $this->assertStatusEqualsCode(Status::error(), 'SUMAS09');
        $this->assertStatusEqualsCode(Status::error(), 'SUMAS10');
    }

    public function testValidateUnsetTotalImpuestosTrasladados(): void
    {
        $this->setupCfdiFile('cfdi33-valid.xml');
        if (null !== $impuestos = $this->comprobante->searchNode('cfdi:Impuestos')) {
            $impuestos['TotalImpuestosTrasladados'] = '123456.78';
        }
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'SUMAS04');
    }

    public function testValidateUnsetOneImpuestosTrasladados(): void
    {
        $this->setupCfdiFile('cfdi33-valid.xml');
        $traslados = $this->comprobante->searchNode('cfdi:Impuestos', 'cfdi:Traslados');
        if (null !== $traslados) {
            $traslado = $traslados->searchNode('cfdi:Traslado');
            if (null !== $traslado) {
                $traslados->children()->remove($traslado);
            }
        }
        $this->assertNull($this->comprobante->searchNode('cfdi:Impuestos', 'cfdi:Traslados', 'cfdi:Traslado'));
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'SUMAS05');
    }

    public function testValidateBadOneImpuestosTrasladados(): void
    {
        $this->setupCfdiFile('cfdi33-valid.xml');
        $traslados = $this->comprobante->searchNode('cfdi:Impuestos', 'cfdi:Traslados');
        if (null !== $traslados) {
            $traslado = $traslados->searchNode('cfdi:Traslado');
            if (null !== $traslado) {
                $traslado['Importe'] = '123456.78';
            }
        }
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'SUMAS06');
    }

    public function testValidateMoreImpuestosTrasladados(): void
    {
        $this->setupCfdiFile('cfdi33-valid.xml');
        $traslados = $this->comprobante->searchNode('cfdi:Impuestos', 'cfdi:Traslados');
        if (null !== $traslados) {
            $traslados->addChild(new Node('cfdi:Traslado', [
                'Base' => '1000.00',
                'Impuesto' => 'XXX',
                'TipoFactor' => '0.050000',
                'TasaOCuota' => 'tasa',
                'Importe' => '50.00',
            ]));
        }

        $this->runValidate();
        $assert = $this->getAssertByCodeOrFail('SUMAS07');

        $this->assertTrue($assert->getStatus()->isError());
        $this->assertEquals(
            'No encontrados: 1 impuestos. Impuesto: XXX, TipoFactor: 0.050000, TasaOCuota: tasa, Importe: 50.00.',
            $assert->getExplanation()
        );
    }

    public function testValidateUnsetTotalImpuestosRetenidos(): void
    {
        $this->setupCfdiFile('cfdi33-valid.xml');
        $impuestos = $impuestos = $this->comprobante->searchNode('cfdi:Impuestos');
        if (null !== $impuestos) {
            $impuestos['TotalImpuestosRetenidos'] = '123456.78';
        }
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'SUMAS08');
    }

    public function testValidateUnsetOneImpuestosRetenidos(): void
    {
        $this->setupCfdiFile('cfdi33-valid.xml');
        $retenciones = $this->comprobante->searchNode('cfdi:Impuestos', 'cfdi:Retenciones');
        if (null !== $retenciones) {
            $retencion = $retenciones->searchNode('cfdi:Retencion');
            if (null !== $retencion) {
                $retenciones->children()->remove($retencion);
            }
        }
        $this->assertNull($this->comprobante->searchNode('cfdi:Impuestos', 'cfdi:Retenciones', 'cfdi:Retencion'));
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'SUMAS09');
    }

    public function testValidateBadOneImpuestosRetenidos(): void
    {
        $this->setupCfdiFile('cfdi33-valid.xml');
        $retenciones = $this->comprobante->searchNode('cfdi:Impuestos', 'cfdi:Retenciones');
        if (null !== $retenciones) {
            $retencion = $retenciones->searchNode('cfdi:Retencion');
            if (null !== $retencion) {
                $retencion['Importe'] = '123456.78';
            }
        }
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'SUMAS10');
    }

    public function testValidateMoreImpuestosRetenciones(): void
    {
        $this->setupCfdiFile('cfdi33-valid.xml');
        $retenciones = $this->comprobante->searchNode('cfdi:Impuestos', 'cfdi:Retenciones');
        if (null !== $retenciones) {
            $retenciones->addChild(new Node('cfdi:Retencion', [
                'Base' => '1000.00',
                'Impuesto' => 'XXX',
                'TipoFactor' => '0.050000',
                'TasaOCuota' => 'tasa',
                'Importe' => '50.00',
            ]));
        }
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'SUMAS11');
    }

    public function providerValidateDescuentoLessOrEqualThanSubTotal(): array
    {
        return [
            'greater' => ['12345.679', '12345.678', Status::error()],
            'equal' => ['12345.678', '12345.678', Status::ok()],
            'less' => ['12345.677', '12345.678', Status::ok()],
            'empty' => ['', '12345.678', Status::ok()],
            'zeros' => ['0.00', '0.00', Status::ok()],
        ];
    }

    /**
     * @param string $descuento
     * @param string $subtotal
     * @param Status $expected
     * @dataProvider providerValidateDescuentoLessOrEqualThanSubTotal
     */
    public function testValidateDescuentoLessOrEqualThanSubTotal(
        string $descuento,
        string $subtotal,
        Status $expected
    ): void {
        $this->comprobante->addAttributes([
            'SubTotal' => $subtotal,
            'Descuento' => $descuento,
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode($expected, 'SUMAS12');
    }
}
