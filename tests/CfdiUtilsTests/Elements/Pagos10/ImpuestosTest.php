<?php

namespace CfdiUtilsTests\Elements\Pagos10;

use CfdiUtils\Elements\Pagos10\Impuestos;
use CfdiUtils\Elements\Pagos10\Retenciones;
use CfdiUtils\Elements\Pagos10\Traslados;
use PHPUnit\Framework\TestCase;

final class ImpuestosTest extends TestCase
{
    /** @var Impuestos */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new Impuestos();
    }

    public function testConstructedObject()
    {
        $this->assertSame('pago10:Impuestos', $this->element->getElementName());
    }

    public function testGetTraslados()
    {
        $this->assertNull($this->element->searchNode('pago10:Traslados'));
        $child = $this->element->getTraslados();
        $this->assertInstanceOf(Traslados::class, $child);
        $this->assertSame($child, $this->element->searchNode('pago10:Traslados'));
    }

    public function testGetRetenciones()
    {
        $this->assertNull($this->element->searchNode('pago10:Retenciones'));
        $child = $this->element->getRetenciones();
        $this->assertInstanceOf(Retenciones::class, $child);
        $this->assertSame($child, $this->element->searchNode('pago10:Retenciones'));
    }

    public function testChildrenOrder()
    {
        // add in inverse order
        $this->element->getTraslados();
        $this->element->getRetenciones();

        // retrieve in correct order
        $this->assertInstanceOf(Retenciones::class, $this->element->children()->get(0));
        $this->assertInstanceOf(Traslados::class, $this->element->children()->get(1));
    }
}
