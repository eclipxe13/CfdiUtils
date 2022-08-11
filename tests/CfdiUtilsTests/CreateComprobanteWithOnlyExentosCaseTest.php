<?php

namespace CfdiUtilsTests;

use CfdiUtils\CfdiCreator33;
use CfdiUtils\CfdiCreator40;
use CfdiUtils\Nodes\Node;
use CfdiUtils\Nodes\XmlNodeUtils;

final class CreateComprobanteWithOnlyExentosCaseTest extends TestCase
{
    public function testCreateCcomprobante33WithOnlyExentosWriteImpuestos(): void
    {
        $creator = new CfdiCreator33([]);

        $comprobante = $creator->comprobante();
        $comprobante->addConcepto([
            'ClaveProdServ' => '01010101',
            'NoIdentificacion' => 'FOO',
            'Cantidad' => '1',
            'ClaveUnidad' => 'E48',
            'Descripcion' => 'HONORARIOS MEDICOS',
            'ValorUnitario' => '617.000000',
            'Importe' => '617.000000',
            'Descuento' => '144.271240',
            'ObjetoImp' => '02',
        ])->addTraslado([
            'Impuesto' => '002',
            'TipoFactor' => 'Exento',
        ]);

        // test sumasConceptos

        $precision = 2;
        $sumasConceptos = $creator->buildSumasConceptos($precision);
        $this->assertTrue($sumasConceptos->hasExentos());
        $this->assertFalse($sumasConceptos->hasTraslados());
        $this->assertFalse($sumasConceptos->hasRetenciones());

        $expectedExentos = [
            '002:Exento:' => [
                'TipoFactor' => 'Exento',
                'Impuesto' => '002',
                'Base' => 0,
            ],
        ];

        $this->assertEquals($expectedExentos, $sumasConceptos->getExentos());

        // test cfdi:Impuestos XML does not exists

        $creator->addSumasConceptos($sumasConceptos, $precision);

        $this->assertNull($comprobante->searchNode('cfdi:Impuestos'));
    }

    public function testCreateCcomprobante40WithOnlyExentosWriteImpuestos(): void
    {
        $creator = new CfdiCreator40([]);

        $comprobante = $creator->comprobante();
        $comprobante->addConcepto([
            'ClaveProdServ' => '01010101',
            'NoIdentificacion' => 'FOO',
            'Cantidad' => '1',
            'ClaveUnidad' => 'E48',
            'Descripcion' => 'HONORARIOS MEDICOS',
            'ValorUnitario' => '617.000000',
            'Importe' => '617.000000',
            'Descuento' => '144.271240',
            'ObjetoImp' => '02',
        ])->addTraslado([
            'Base' => '472.728760',
            'Impuesto' => '002',
            'TipoFactor' => 'Exento',
        ]);

        // test sumasConceptos

        $precision = 2;
        $sumasConceptos = $creator->buildSumasConceptos($precision);
        $this->assertTrue($sumasConceptos->hasExentos());
        $this->assertFalse($sumasConceptos->hasTraslados());
        $this->assertFalse($sumasConceptos->hasRetenciones());

        $expectedExentos = [
            '002:Exento:' => [
                'TipoFactor' => 'Exento',
                'Impuesto' => '002',
                'Base' => 472.72876,
            ],
        ];

        $this->assertEquals($expectedExentos, $sumasConceptos->getExentos());

        // test cfdi:Impuestos XML

        $creator->addSumasConceptos($sumasConceptos, $precision);

        $expectedImpuestosNode = new Node('cfdi:Impuestos', [], [
            new Node('cfdi:Traslados', [], [
                new Node('cfdi:Traslado', ['TipoFactor' => 'Exento', 'Impuesto' => '002', 'Base' => '472.73']),
            ]),
        ]);

        $this->assertXmlStringEqualsXmlString(
            XmlNodeUtils::nodeToXmlString($expectedImpuestosNode),
            XmlNodeUtils::nodeToXmlString($comprobante->searchNode('cfdi:Impuestos'))
        );
    }
}
