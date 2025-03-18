<?php

namespace CfdiUtilsTests\SumasConceptos;

use CfdiUtils\Elements\ImpLocal10\ImpuestosLocales;
use CfdiUtils\Nodes\Node;
use CfdiUtils\Nodes\XmlNodeUtils;
use CfdiUtils\SumasConceptos\SumasConceptos;
use CfdiUtils\SumasConceptos\SumasConceptosWriter;

trait SumasConceptosWriterTestTrait
{
    public function testFormat(): void
    {
        $precision = 6;
        $comprobante = $this->createComprobante();
        $sumasConceptos = new SumasConceptos($comprobante, $precision);
        $writer = new SumasConceptosWriter($comprobante, $sumasConceptos, $precision);

        $this->assertSame('1.234566', $writer->format(1.2345664));
        $this->assertSame('1.234567', $writer->format(1.2345665));
        $this->assertSame('1.234567', $writer->format(1.2345674));
        $this->assertSame('1.234568', $writer->format(1.2345675));
        $this->assertSame('1.000000', $writer->format(1));
    }

    public function testPutWithEmptyValues(): void
    {
        $precision = 2;
        $comprobante = $this->createComprobante();
        $sumasConceptos = new SumasConceptos($comprobante, $precision);
        $writer = new SumasConceptosWriter($comprobante, $sumasConceptos, $precision);
        $writer->put();

        $this->assertSame('0.00', $comprobante['SubTotal']);
        $this->assertFalse(isset($comprobante['Descuento']));
        $this->assertSame('0.00', $comprobante['Total']);
        $this->assertNull($comprobante->searchNode('cfdi:Impuestos'));
    }

    public function testPutWithEmptyConceptosImpuestos(): void
    {
        $precision = 2;
        $comprobante = $this->createComprobante();
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

    public function testPutWithZeroConceptosImpuestos(): void
    {
        $precision = 2;
        $comprobante = $this->createComprobante();
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

    public function testPutWithConceptosImpuestos(): void
    {
        $precision = 2;
        $comprobante = $this->createComprobante();
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

    public function testDescuentoWithValueZeroExistsIfAConceptoHasDescuento(): void
    {
        $comprobante = $this->createComprobante();
        $comprobante->addConcepto([]); // first concepto does not have Descuento
        $comprobante->addConcepto(['Descuento' => '']); // second concepto has Descuento

        $precision = 2;
        $sumasConceptos = new SumasConceptos($comprobante, $precision);
        $writer = new SumasConceptosWriter($comprobante, $sumasConceptos, $precision);
        $writer->put();

        $this->assertSame('0.00', $comprobante['Descuento']);
    }

    public function testDescuentoNotSetIfAllConceptosDoesNotHaveDescuento(): void
    {
        $comprobante = $this->createComprobante(['Descuento' => '']); // set value with discount
        $comprobante->addConcepto(); // first concepto does not have Descuento
        $comprobante->addConcepto(); // second concepto does not have Descuento neither

        $precision = 2;
        $sumasConceptos = new SumasConceptos($comprobante, $precision);
        $writer = new SumasConceptosWriter($comprobante, $sumasConceptos, $precision);
        $writer->put();

        // the Comprobante@Descuento attribute must not exist since there is no Descuento in concepts
        $this->assertFalse(isset($comprobante['Descuento']));
    }

    public function testOnComplementoImpuestosImporteSumIsRoundedCfdi(): void
    {
        $comprobante = $this->createComprobante();
        $comprobante->addConcepto()->multiTraslado(
            [
                'Base' => '48.611106',
                'Importe' => '7.777777',
                'Impuesto' => '002',
                'TipoFactor' => 'Tasa',
                'TasaOCuota' => '0.160000',
            ],
            [
                'Base' => '48.611106',
                'Impuesto' => '002',
                'TipoFactor' => 'Exento',
            ],
        );
        $comprobante->addConcepto()->multiTraslado(
            [
                'Base' => '13.888888',
                'Importe' => '2.222222',
                'Impuesto' => '002',
                'TipoFactor' => 'Tasa',
                'TasaOCuota' => '0.160000',
            ],
            [
                'Base' => '13.888888',
                'Impuesto' => '002',
                'TipoFactor' => 'Exento',
            ],
        );

        $precision = 3;
        $sumasConceptos = new SumasConceptos($comprobante, $precision);
        $writer = new SumasConceptosWriter($comprobante, $sumasConceptos, $precision);
        $writer->put();

        $traslado = $comprobante->searchNode('cfdi:Impuestos', 'cfdi:Traslados', 'cfdi:Traslado');
        $this->assertSame('10.000', $comprobante->searchAttribute('cfdi:Impuestos', 'TotalImpuestosTrasladados'));
        $this->assertSame('10.000', $traslado['Importe']);
        if ($writer->hasWriteImpuestoBase()) {
            $this->assertSame('62.500', $traslado['Base']);
        } else {
            $this->assertFalse(isset($traslado['Base']));
        }

        if ($writer->hasWriteExentos()) {
            $exento = $comprobante->searchNodes('cfdi:Impuestos', 'cfdi:Traslados', 'cfdi:Traslado')->get(1);
            $this->assertSame('62.500', $exento['Base']);
        }
    }

    public function testConceptosOnlyWithTrasladosExentosDoesNotWriteTraslados(): void
    {
        $comprobante = $this->createComprobante();
        $concepto = $comprobante->addConcepto();
        $concepto->addTraslado(['Base' => '1000', 'Impuesto' => '002', 'TipoFactor' => 'Exento']);
        $concepto->addRetencion([
            'Base' => '1000.00',
            'Impuesto' => '001',
            'TipoFactor' => 'Tasa',
            'TasaOCuota' => '0.04000',
            'Importe' => '40.00',
        ]);
        $comprobante->addConcepto()->addTraslado(['Base' => '1000', 'Impuesto' => '002', 'TipoFactor' => 'Exento']);

        $precision = 2;
        $sumasConceptos = new SumasConceptos($comprobante, $precision);
        $writer = new SumasConceptosWriter($comprobante, $sumasConceptos, $precision);
        $writer->put();

        $this->assertSame(
            $writer->hasWriteExentos(),
            $writer->hasWriteImpuestoBase(),
            'When has to write "exentos" also has to write "impuesto base" and vice versa'
        );

        if ($writer->hasWriteExentos()) {
            $expected = <<<EOT
                <cfdi:Impuestos TotalImpuestosRetenidos="40.00">
                  <cfdi:Retenciones>
                    <cfdi:Retencion Impuesto="001" Importe="40.00"/>
                  </cfdi:Retenciones>
                  <cfdi:Traslados>
                    <cfdi:Traslado Impuesto="002" Base="2000.00" TipoFactor="Exento"/>
                  </cfdi:Traslados>
                </cfdi:Impuestos>
                EOT;
        } else {
            $expected = <<<EOT
                <cfdi:Impuestos TotalImpuestosRetenidos="40.00">
                  <cfdi:Retenciones>
                    <cfdi:Retencion Impuesto="001" Importe="40.00"/>
                  </cfdi:Retenciones>
                </cfdi:Impuestos>
                EOT;
        }
        $this->assertXmlStringEqualsXmlString($expected, XmlNodeUtils::nodeToXmlString($comprobante->getImpuestos()));
    }

    public function testSetRequiredImpLocalAttributes(): void
    {
        $comprobante = $this->createComprobante();
        $impLocal = new ImpuestosLocales();
        for ($i = 0; $i < 2; $i++) {
            $impLocal->addTrasladoLocal([
                'ImpLocTrasladado' => 'IH',
                'Importe' => '27.43',
                'TasadeTraslado' => '2.50',
            ]);
            $impLocal->addRetencionLocal([
                'ImpLocTrasladado' => 'IH',
                'Importe' => '27.43',
                'TasadeTraslado' => '2.50',
            ]);
        }
        $comprobante->addComplemento($impLocal);

        $precision = 2;
        $sumas = new SumasConceptos($comprobante, $precision);
        $writer = new SumasConceptosWriter($comprobante, $sumas, $precision);
        $writer->put();

        $this->assertSame('54.86', $impLocal->attributes()->get('TotaldeRetenciones'));
        $this->assertSame('54.86', $impLocal->attributes()->get('TotaldeTraslados'));
    }

    public function testRemoveImpLocalComplementWhenIsEmptyAndPreservesOthersComplements(): void
    {
        $comprobante = $this->createComprobante();
        $comprobante->addComplemento(new Node('other:PrimerComplemento'));
        $comprobante->addComplemento(new ImpuestosLocales());
        $comprobante->addComplemento(new Node('other:UltimoComplemento'));

        $precision = 2;
        $sumas = new SumasConceptos($comprobante, $precision);
        $writer = new SumasConceptosWriter($comprobante, $sumas, $precision);
        $writer->put();

        $this->assertCount(2, $comprobante->getComplemento());
        $this->assertNotNull($comprobante->searchNode('cfdi:Complemento', 'other:PrimerComplemento'));
        $this->assertNotNull($comprobante->searchNode('cfdi:Complemento', 'other:UltimoComplemento'));
        $this->assertNull($comprobante->searchNode('cfdi:Complemento', 'implocal:ImpuestosLocales'));
    }

    public function testRemoveImpLocalComplementAndRemoveComplementoNodeWhenIsEmpty(): void
    {
        $comprobante = $this->createComprobante();
        $comprobante->addComplemento(new ImpuestosLocales());

        $precision = 2;
        $sumas = new SumasConceptos($comprobante, $precision);
        $writer = new SumasConceptosWriter($comprobante, $sumas, $precision);
        $writer->put();

        $this->assertNull($comprobante->searchNode('cfdi:Complemento'));
    }
}
