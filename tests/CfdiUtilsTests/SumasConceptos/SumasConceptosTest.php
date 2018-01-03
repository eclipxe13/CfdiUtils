<?php
namespace CfdiUtilsTests\SumasConceptos;

use CfdiUtils\Elements\Cfdi33\Comprobante;
use CfdiUtils\Nodes\Node;
use CfdiUtils\SumasConceptos\SumasConceptos;
use PHPUnit\Framework\TestCase;

class SumasConceptosTest extends TestCase
{
    public function testConstructor()
    {
        $maxDiff = 0.0000001;
        $sc = new SumasConceptos(new Node('x'));
        $this->assertSame(2, $sc->getPrecision());
        $this->assertEquals(0, $sc->getSubTotal(), '', $maxDiff);
        $this->assertEquals(0, $sc->getTotal(), '', $maxDiff);
        $this->assertEquals(0, $sc->getDescuento(), '', $maxDiff);
        $this->assertEquals(0, $sc->getImpuestosRetenidos(), '', $maxDiff);
        $this->assertEquals(0, $sc->getImpuestosTrasladados(), '', $maxDiff);
        $this->assertCount(0, $sc->getRetenciones());
        $this->assertCount(0, $sc->getTraslados());
    }

    public function providerWithConceptsDecimals()
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
     * @param $taxDecimals
     * @param $subtotal
     * @param $traslados
     * @param $total
     * @dataProvider providerWithConceptsDecimals
     */
    public function testWithConceptsDecimals($taxDecimals, $subtotal, $traslados, $total)
    {
        $maxDiff = 0.0000001;
        $comprobante = new Comprobante();
        $comprobante->addConcepto([
            'Importe' => '111.11',
        ])->addTraslado([
            'Impuesto' => '002',
            'TipoFactor' => 'Tasa',
            'TasaOCuota' => '0.160000',
            'Importe' => number_format(111.11 * 0.16, $taxDecimals, '.', ''),
        ]);
        $comprobante->addConcepto([
            'Importe' => '222.22',
        ])->addTraslado([
            'Impuesto' => '002',
            'TipoFactor' => 'Tasa',
            'TasaOCuota' => '0.160000',
            'Importe' => number_format(222.22 * 0.16, $taxDecimals, '.', ''),
        ]);
        $sc = new SumasConceptos($comprobante, 2);
        $this->assertEquals($subtotal, $sc->getSubTotal(), '', $maxDiff);
        $this->assertEquals($traslados, $sc->getImpuestosTrasladados(), '', $maxDiff);
        $this->assertEquals($total, $sc->getTotal(), '', $maxDiff);
        // this are zero
        $this->assertEquals(0, $sc->getDescuento(), '', $maxDiff);
        $this->assertEquals(0, $sc->getImpuestosRetenidos(), '', $maxDiff);
        $this->assertCount(0, $sc->getRetenciones());
    }
}
