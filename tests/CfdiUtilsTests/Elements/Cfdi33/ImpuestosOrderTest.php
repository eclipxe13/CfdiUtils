<?php

namespace CfdiUtilsTests\Elements\Cfdi33;

use CfdiUtils\Elements\Cfdi33\Comprobante;
use CfdiUtils\Elements\Cfdi33\Concepto;
use CfdiUtils\Elements\Cfdi33\ConceptoImpuestos;
use CfdiUtils\Elements\Cfdi33\Impuestos;
use PHPUnit\Framework\TestCase;

class ImpuestosOrderTest extends TestCase
{
    public function testComprobanteImpuestosOrderIsRetencionesTraslados()
    {
        $comprobante = new Comprobante();
        $impuestos = $comprobante->getImpuestos();
        $this->assertInstanceOf(Impuestos::class, $impuestos);
        $expectedOrder = ['cfdi:Retenciones', 'cfdi:Traslados'];

        $this->assertSame($expectedOrder, $impuestos->getChildrenOrder());
    }

    public function testConceptoImpuestosOrderIsTrasladosRetenciones()
    {
        $concepto = new Concepto();
        $impuestos = $concepto->getImpuestos();
        $this->assertInstanceOf(Impuestos::class, $impuestos);
        $this->assertInstanceOf(ConceptoImpuestos::class, $impuestos);
        $expectedOrder = ['cfdi:Traslados', 'cfdi:Retenciones'];

        $this->assertSame($expectedOrder, $impuestos->getChildrenOrder());
    }
}
