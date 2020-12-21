<?php

namespace CfdiUtilsTests\Elements\Cfdi33\Traits;

use CfdiUtils\Elements\Cfdi33\Impuestos;
use CfdiUtils\Elements\Cfdi33\Retencion;
use CfdiUtils\Elements\Cfdi33\Retenciones;
use CfdiUtils\Elements\Cfdi33\Traslado;
use CfdiUtils\Elements\Cfdi33\Traslados;
use PHPUnit\Framework\TestCase;

class ImpuestosTraitTest extends TestCase
{
    /** @var UseImpuestos */
    public $element;

    public function setUp(): void
    {
        parent::setUp();
        $this->element = new UseImpuestos();
    }

    public function testGetImpuestos()
    {
        $this->assertNull($this->element->searchNode('cfdi:Impuestos'));
        $child = $this->element->getImpuestos();
        $this->assertInstanceOf(Impuestos::class, $child);
        $this->assertSame($child, $this->element->searchNode('cfdi:Impuestos'));
    }

    public function testAddTraslado()
    {
        $this->assertNull($this->element->searchNode('cfdi:Impuestos', 'cfdi:Traslados', 'cfdi:Traslado'));
        $first = $this->element->addTraslado(['name' => 'first']);
        $this->assertInstanceOf(Traslado::class, $first);
        $this->assertSame('first', $first['name']);
        $this->assertSame($first, $this->element->searchNode('cfdi:Impuestos', 'cfdi:Traslados', 'cfdi:Traslado'));
    }

    public function testMultiTraslado()
    {
        $parent = $this->element->getImpuestos()->getTraslados();
        $this->assertCount(0, $parent);
        $multiReturn = $this->element->multiTraslado(
            ['id' => 'first'],
            ['id' => 'second']
        );
        $this->assertSame($multiReturn, $this->element);
        $this->assertCount(2, $parent);
        $this->assertSame('first', $parent->searchAttribute('cfdi:Traslado', 'id'));
    }

    public function testAddRetencion()
    {
        $this->assertNull($this->element->searchNode('cfdi:Impuestos', 'cfdi:Retenciones', 'cfdi:Retencion'));
        $first = $this->element->addRetencion(['name' => 'first']);
        $this->assertInstanceOf(Retencion::class, $first);
        $this->assertSame('first', $first['name']);
        $this->assertSame($first, $this->element->searchNode('cfdi:Impuestos', 'cfdi:Retenciones', 'cfdi:Retencion'));
    }

    public function testMultiRetencion()
    {
        $parent = $this->element->getImpuestos()->getRetenciones();
        $this->assertCount(0, $parent);
        $multiReturn = $this->element->multiRetencion(
            ['id' => 'first'],
            ['id' => 'second']
        );
        $this->assertSame($multiReturn, $this->element);
        $this->assertCount(2, $parent);
        $this->assertSame('first', $parent->searchAttribute('cfdi:Retencion', 'id'));
    }

    public function testChildrenOrder()
    {
        // add in inverse order
        $this->element->addRetencion();
        $this->element->addTraslado();

        // retrieve in correct order
        $impuestos = $this->element->getImpuestos();
        $this->assertInstanceOf(Retenciones::class, $impuestos->children()->get(0));
        $this->assertInstanceOf(Traslados::class, $impuestos->children()->get(1));
    }
}
