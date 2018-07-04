<?php
namespace CfdiUtilsTests\Elements\Cfdi33\Helpers;

use CfdiUtils\Elements\Cfdi33\Comprobante;
use CfdiUtils\Elements\Cfdi33\Helpers\SumasConceptosWriter;
use CfdiUtils\SumasConceptos\SumasConceptos;
use PHPUnit\Framework\TestCase;

class SumasConceptosWriterTest extends TestCase
{
    public function testConstructor()
    {
        $precision = 6;
        $comprobante = new Comprobante();
        $sumasConceptos = new SumasConceptos($comprobante, $precision);
        $writer = new SumasConceptosWriter($comprobante, $sumasConceptos, $precision);

        $this->assertSame($precision, $writer->getPrecision());
        $this->assertSame($sumasConceptos, $writer->getSumasConceptos());
        $this->assertSame($precision, $writer->getPrecision());
    }

    public function testFormat()
    {
        $precision = 6;
        $comprobante = new Comprobante();
        $sumasConceptos = new SumasConceptos($comprobante, $precision);
        $writer = new SumasConceptosWriter($comprobante, $sumasConceptos, $precision);

        $this->assertSame('1.234566', $writer->format(1.2345664));
        $this->assertSame('1.234567', $writer->format(1.2345665));
        $this->assertSame('1.234567', $writer->format(1.2345674));
        $this->assertSame('1.234568', $writer->format(1.2345675));
        $this->assertSame('1.000000', $writer->format(1));
    }

    public function testPutWithEmptyValues()
    {
        $precision = 2;
        $comprobante = new Comprobante();
        $sumasConceptos = new SumasConceptos($comprobante, $precision);
        $writer = new SumasConceptosWriter($comprobante, $sumasConceptos, $precision);
        $writer->put();

        $this->assertSame('0.00', $comprobante['SubTotal']);
        $this->assertFalse(isset($comprobante['Descuento']));
        $this->assertSame('0.00', $comprobante['Total']);
        $this->assertNull($comprobante->searchNode('cfdi:Impuestos'));
    }

    public function testPutWithEmptyConceptosImpuestos()
    {
        $precision = 2;
        $comprobante = new Comprobante();
        $comprobante->addConcepto([
            'Importe' => 1000,
            'Descuento' => 1000,
        ]);
        $comprobante->addConcepto([
            'Importe' => 2000,
            'Descuento' => 2000,
        ]);

        $sumasConceptos = new SumasConceptos($comprobante, $precision);
        $writer = new SumasConceptosWriter($comprobante, $sumasConceptos, $precision);
        $writer->put();

        $this->assertSame('3000.00', $comprobante['SubTotal']);
        $this->assertSame('3000.00', $comprobante['Descuento']);
        $this->assertSame('0.00', $comprobante['Total']);
        $this->assertNull($comprobante->searchNode('cfdi:Impuestos'));
    }

    public function testPutWithZeroConceptosImpuestos()
    {
        $precision = 2;
        $comprobante = new Comprobante();
        $comprobante->addConcepto([
            'Importe' => '1000',
        ])->addTraslado([
            'Base' => '1000',
            'Impuesto' => '002',
            'TipoFactor' => 'Tasa',
            'TasaOCuota' => '0.000000',
            'Importe' => '0',
        ]);
        $comprobante->addConcepto([
            'Importe' => '2000',
        ])->addTraslado([
            'Base' => '2000',
            'Impuesto' => '002',
            'TipoFactor' => 'Tasa',
            'TasaOCuota' => '0.000000',
            'Importe' => '0',
        ]);

        $sumasConceptos = new SumasConceptos($comprobante, $precision);
        $writer = new SumasConceptosWriter($comprobante, $sumasConceptos, $precision);
        $writer->put();

        $this->assertSame('3000.00', $comprobante['SubTotal']);
        $this->assertFalse(isset($comprobante['Descuento']));
        $this->assertSame('3000.00', $comprobante['Total']);
        $this->assertNotNull($comprobante->searchNode('cfdi:Impuestos'));
        $impuestos = $comprobante->getImpuestos();
        $this->assertTrue(isset($impuestos['TotalImpuestosTrasladados']));
        $this->assertSame('0.00', $impuestos['TotalImpuestosTrasladados']);
        $this->assertFalse(isset($impuestos['TotalImpuestosRetenidos']));
    }

    public function testPutWithConceptosImpuestos()
    {
        $precision = 2;
        $comprobante = new Comprobante();
        $comprobante->addConcepto([
            'Importe' => '2000',
            'Descuento' => '1000',
        ])->addTraslado([
            'Base' => '1000',
            'Impuesto' => '002',
            'TipoFactor' => 'Tasa',
            'TasaOCuota' => '0.160000',
            'Importe' => '160',
        ]);
        $comprobante->addConcepto([
            'Importe' => '4000',
            'Descuento' => '2000',
        ])->addTraslado([
            'Base' => '2000',
            'Impuesto' => '002',
            'TipoFactor' => 'Tasa',
            'TasaOCuota' => '0.160000',
            'Importe' => '320',
        ]);

        $sumasConceptos = new SumasConceptos($comprobante, $precision);
        $writer = new SumasConceptosWriter($comprobante, $sumasConceptos, $precision);
        $writer->put();

        $this->assertSame('6000.00', $comprobante['SubTotal']);
        $this->assertSame('3000.00', $comprobante['Descuento']);
        $this->assertSame('3480.00', $comprobante['Total']);
        $this->assertNotNull($comprobante->searchNode('cfdi:Impuestos'));
        $impuestos = $comprobante->getImpuestos();
        $this->assertTrue(isset($impuestos['TotalImpuestosTrasladados']));
        $this->assertSame('480.00', $impuestos['TotalImpuestosTrasladados']);
        $this->assertFalse(isset($impuestos['TotalImpuestosRetenidos']));
    }

    public function testDescuentoWithValueZeroExistsIfAConceptoHasDescuento()
    {
        $comprobante = new Comprobante();
        $comprobante->addConcepto([]); // first concepto does not have Descuento
        $comprobante->addConcepto(['Descuento' => '']); // second concepto has Descuento

        $precision = 2;
        $sumasConceptos = new SumasConceptos($comprobante, $precision);
        $writer = new SumasConceptosWriter($comprobante, $sumasConceptos, $precision);
        $writer->put();

        $this->assertSame('0.00', $comprobante['Descuento']);
    }

    public function testDescuentoNotSetIfAllConceptosDoesNotHaveDescuento()
    {
        $comprobante = new Comprobante(['Descuento' => '']); // set value with discount
        $comprobante->addConcepto(); // first concepto does not have Descuento
        $comprobante->addConcepto(); // second concepto does not have Descuento neither

        $precision = 2;
        $sumasConceptos = new SumasConceptos($comprobante, $precision);
        $writer = new SumasConceptosWriter($comprobante, $sumasConceptos, $precision);
        $writer->put();

        // the Comprobante@Descuento attribute must not exists since there is no Descuento in concepts
        $this->assertFalse(isset($comprobante['Descuento']));
    }

    public function testOnComplementoImpuestosImporteSumIsRounded()
    {
        $comprobante = new Comprobante();
        $comprobante->addConcepto()->addTraslado(
            ['Importe' => '7.777777', 'Impuesto' => '002', 'TipoFactor' => 'Tasa', 'TasaOCuota' => '0.160000']
        );
        $comprobante->addConcepto()->addTraslado(
            ['Importe' => '2.222222', 'Impuesto' => '002', 'TipoFactor' => 'Tasa', 'TasaOCuota' => '0.160000']
        );

        $precision = 3;
        $sumasConceptos = new SumasConceptos($comprobante, $precision);
        $writer = new SumasConceptosWriter($comprobante, $sumasConceptos, $precision);
        $writer->put();

        $this->assertSame('10.000', $comprobante->searchAttribute('cfdi:Impuestos', 'TotalImpuestosTrasladados'));
        $this->assertSame(
            '10.000',
            $comprobante->searchAttribute('cfdi:Impuestos', 'cfdi:Traslados', 'cfdi:Traslado', 'Importe')
        );
    }
}
