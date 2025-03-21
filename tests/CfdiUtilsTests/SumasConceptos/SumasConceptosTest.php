<?php

namespace CfdiUtilsTests\SumasConceptos;

use CfdiUtils\Elements\Cfdi33\Comprobante;
use CfdiUtils\Elements\ImpLocal10\ImpuestosLocales;
use CfdiUtils\Nodes\Node;
use CfdiUtils\SumasConceptos\SumasConceptos;
use PHPUnit\Framework\TestCase;

final class SumasConceptosTest extends TestCase
{
    public function testConstructor(): void
    {
        $maxDiff = 0.0000001;
        $sc = new SumasConceptos(new Node('x'));
        $this->assertSame(2, $sc->getPrecision());
        $this->assertEqualsWithDelta(0, $sc->getSubTotal(), $maxDiff);
        $this->assertEqualsWithDelta(0, $sc->getTotal(), $maxDiff);
        $this->assertEqualsWithDelta(0, $sc->getDescuento(), $maxDiff);
        $this->assertEqualsWithDelta(0, $sc->getImpuestosRetenidos(), $maxDiff);
        $this->assertEqualsWithDelta(0, $sc->getImpuestosTrasladados(), $maxDiff);
        $this->assertEqualsWithDelta(0, $sc->getLocalesImpuestosRetenidos(), $maxDiff);
        $this->assertEqualsWithDelta(0, $sc->getLocalesImpuestosTrasladados(), $maxDiff);
        $this->assertCount(0, $sc->getRetenciones());
        $this->assertCount(0, $sc->getTraslados());
        $this->assertCount(0, $sc->getExentos());
        $this->assertCount(0, $sc->getLocalesRetenciones());
        $this->assertCount(0, $sc->getLocalesTraslados());
        $this->assertFalse($sc->hasRetenciones());
        $this->assertFalse($sc->hasTraslados());
        $this->assertFalse($sc->hasExentos());
        $this->assertFalse($sc->hasLocalesRetenciones());
        $this->assertFalse($sc->hasLocalesTraslados());
    }

    public function providerWithConceptsDecimals(): array
    {
        /*
         * The case "tax uses 1 dec" 53.4 = round(35.6 + 17.8, 2)
         * The case "tax uses 6 dec" 53.33 = round(17.7776 + 35.5552, 2)
         */
        return [
            'tax uses 1 dec' => [1, 333.33, 53.4, 386.73],
            'tax uses 6 dec' => [6, 333.33, 53.33, 386.66],
        ];
    }

    /**
     * @dataProvider providerWithConceptsDecimals
     */
    public function testWithConceptsDecimals(int $taxDecimals, float $subtotal, float $traslados, float $total): void
    {
        $maxDiff = 0.0000001;
        $comprobante = new Comprobante();
        $comprobante->addConcepto([
            'Importe' => '111.11',
        ])->addTraslado([
            'Base' => '111.11',
            'Impuesto' => '002',
            'TipoFactor' => 'Tasa',
            'TasaOCuota' => '0.160000',
            'Importe' => number_format(111.11 * 0.16, $taxDecimals, '.', ''),
        ]);
        $comprobante->addConcepto([
            'Importe' => '222.22',
        ])->addTraslado([
            'Base' => '222.22',
            'Impuesto' => '002',
            'TipoFactor' => 'Tasa',
            'TasaOCuota' => '0.160000',
            'Importe' => number_format(222.22 * 0.16, $taxDecimals, '.', ''),
        ]);
        $sc = new SumasConceptos($comprobante, 2);
        $this->assertEqualsWithDelta($subtotal, $sc->getSubTotal(), $maxDiff);
        $this->assertEqualsWithDelta($traslados, $sc->getImpuestosTrasladados(), $maxDiff);
        $this->assertEqualsWithDelta($total, $sc->getTotal(), $maxDiff);
        // these are zero
        $this->assertEqualsWithDelta(0, $sc->getDescuento(), $maxDiff);
        $this->assertEqualsWithDelta(0, $sc->getImpuestosRetenidos(), $maxDiff);
        $this->assertCount(0, $sc->getRetenciones());
        $this->assertCount(0, $sc->getExentos());
    }

    public function testWithImpuestosLocales(): void
    {
        $taxDecimals = 4;
        $maxDiff = 0.0000001;
        $comprobante = new Comprobante();
        $comprobante->addConcepto([
            'Importe' => '111.11',
        ])->addTraslado([
            'Base' => '111.11',
            'Impuesto' => '002',
            'TipoFactor' => 'Tasa',
            'TasaOCuota' => '0.160000',
            'Importe' => number_format(111.11 * 0.16, $taxDecimals, '.', ''),
        ]);
        $comprobante->addConcepto([
            'Importe' => '222.22',
        ])->addTraslado([
            'Base' => '222.22',
            'Impuesto' => '002',
            'TipoFactor' => 'Tasa',
            'TasaOCuota' => '0.160000',
            'Importe' => number_format(222.22 * 0.16, $taxDecimals, '.', ''),
        ]);
        $impuestosLocales = new ImpuestosLocales();
        $impuestosLocales->addTrasladoLocal([
            'ImpLocTrasladado' => 'IH', // fixed, taken from a sample,
            'TasadeTraslado' => '2.5',
            'Importe' => number_format(333.33 * 0.025, 2, '.', ''),
        ]);
        $comprobante->getComplemento()->add($impuestosLocales);
        $sc = new SumasConceptos($comprobante, 2);

        $this->assertCount(1, $sc->getTraslados());
        $this->assertTrue($sc->hasTraslados());
        $this->assertCount(1, $sc->getLocalesTraslados());

        $this->assertEqualsWithDelta(333.33, $sc->getSubTotal(), $maxDiff);
        $this->assertEqualsWithDelta(53.33, $sc->getImpuestosTrasladados(), $maxDiff);
        $this->assertEqualsWithDelta(8.33, $sc->getLocalesImpuestosTrasladados(), $maxDiff);
        $this->assertEqualsWithDelta(333.33 + 53.33 + 8.33, $sc->getTotal(), $maxDiff);
        // these are zero
        $this->assertEqualsWithDelta(0, $sc->getDescuento(), $maxDiff);
        $this->assertEqualsWithDelta(0, $sc->getImpuestosRetenidos(), $maxDiff);
        $this->assertCount(0, $sc->getRetenciones());
        $this->assertEqualsWithDelta(0, $sc->getLocalesImpuestosRetenidos(), $maxDiff);
        $this->assertCount(0, $sc->getLocalesRetenciones());
    }

    public function testFoundAnyConceptWithDiscount(): void
    {
        $comprobante = new Comprobante();
        $comprobante->addConcepto(['Importe' => '111.11']);
        $comprobante->addConcepto(['Importe' => '222.22']);
        $this->assertFalse((new SumasConceptos($comprobante))->foundAnyConceptWithDiscount());

        // now add the attribute Descuento
        $comprobante->addConcepto(['Importe' => '333.33', 'Descuento' => '']);
        $this->assertTrue((new SumasConceptos($comprobante))->foundAnyConceptWithDiscount());
    }

    public function testImpuestoImporteWithMoreDecimalsThanThePrecisionIsRounded(): void
    {
        $comprobante = new Comprobante();
        $comprobante->addConcepto()->addTraslado([
            'Base' => '48.611106',
            'Importe' => '7.777777',
            'Impuesto' => '002',
            'TipoFactor' => 'Tasa',
            'TasaOCuota' => '0.160000',
        ]);
        $comprobante->addConcepto()->addTraslado([
            'Base' => '13.888888',
            'Importe' => '2.222222',
            'Impuesto' => '002',
            'TipoFactor' => 'Tasa',
            'TasaOCuota' => '0.160000',
        ]);

        $sumas = new SumasConceptos($comprobante, 3);

        $this->assertTrue($sumas->hasTraslados());
        $this->assertEqualsWithDelta(10.0, $sumas->getImpuestosTrasladados(), 0.0001);
        $this->assertEqualsWithDelta(10.0, $sumas->getTraslados()['002:Tasa:0.160000']['Importe'], 0.0000001);
        $this->assertEqualsWithDelta(62.5, $sumas->getTraslados()['002:Tasa:0.160000']['Base'], 0.0000001);
    }

    public function testImpuestoWithTrasladosTasaAndExento(): void
    {
        $comprobante = new Comprobante();
        $comprobante->addConcepto()->multiTraslado(
            [
                'Impuesto' => '002',
                'TipoFactor' => 'Exento',
                'Base' => '1000',
            ],
            [
                'Impuesto' => '002',
                'TipoFactor' => 'Tasa',
                'TasaOCuota' => '0.160000',
                'Base' => '1000',
                'Importe' => '160',
            ]
        );
        $comprobante->addConcepto()->addTraslado([
            'Impuesto' => '002',
            'TipoFactor' => 'Tasa',
            'TasaOCuota' => '0.160000',
            'Base' => '1000',
            'Importe' => '160',
        ]);
        $comprobante->addConcepto()->addTraslado([
            'Impuesto' => '002',
            'TipoFactor' => 'Exento',
            'Base' => '234.56',
        ]);

        $sumas = new SumasConceptos($comprobante, 2);
        $this->assertTrue($sumas->hasTraslados());
        $this->assertEqualsWithDelta(320.0, $sumas->getImpuestosTrasladados(), 0.001);
        $this->assertCount(1, $sumas->getTraslados());

        $this->assertTrue($sumas->hasExentos());
        $this->assertCount(1, $sumas->getExentos());
        $this->assertEqualsWithDelta(1234.56, array_sum(array_column($sumas->getExentos(), 'Base')), 0.001);
    }

    public function testImpuestoWithTrasladosAndOnlyExentosWithoutBase(): void
    {
        $comprobante = new Comprobante();
        $comprobante->addConcepto()->multiTraslado(
            ['Impuesto' => '002', 'TipoFactor' => 'Exento']
        );
        $comprobante->addConcepto()->multiTraslado(
            ['Impuesto' => '002', 'TipoFactor' => 'Exento']
        );

        $sumas = new SumasConceptos($comprobante, 2);
        $this->assertFalse($sumas->hasTraslados());
        $this->assertEqualsWithDelta(0, $sumas->getImpuestosTrasladados(), 0.001);
        $this->assertCount(0, $sumas->getTraslados());

        $this->assertTrue($sumas->hasExentos());
        $this->assertEqualsWithDelta(0, array_sum(array_column($sumas->getExentos(), 'Base')), 0.001);
    }

    public function testImpuestoWithTrasladosAndOnlyExentosWithBase(): void
    {
        $comprobante = new Comprobante();
        $comprobante->addConcepto()->multiTraslado(
            ['Impuesto' => '002', 'TipoFactor' => 'Exento', 'Base' => '123.45'],
        );
        $comprobante->addConcepto()->multiTraslado(
            ['Impuesto' => '002', 'TipoFactor' => 'Exento', 'Base' => '543.21'],
            ['Impuesto' => '001', 'TipoFactor' => 'Exento', 'Base' => '100'],
        );
        $comprobante->addConcepto()->multiTraslado(
            ['Impuesto' => '001', 'TipoFactor' => 'Exento', 'Base' => '150'],
        );

        $sumas = new SumasConceptos($comprobante, 2);
        $this->assertFalse($sumas->hasTraslados());
        $this->assertEqualsWithDelta(0, $sumas->getImpuestosTrasladados(), 0.001);
        $this->assertCount(0, $sumas->getTraslados());

        $this->assertTrue($sumas->hasExentos());

        $exentos001 = array_filter($sumas->getExentos(), fn (array $values): bool => '001' === $values['Impuesto']);
        $this->assertEqualsWithDelta(250.00, array_sum(array_column($exentos001, 'Base')), 0.001);

        $exentos002 = array_filter($sumas->getExentos(), fn (array $values): bool => '002' === $values['Impuesto']);
        $this->assertEqualsWithDelta(666.66, array_sum(array_column($exentos002, 'Base')), 0.001);
    }
}
