<?php

namespace CfdiUtilsTests\Elements\Pagos10;

use CfdiUtils\Elements\Pagos10\Retencion;
use CfdiUtils\Elements\Pagos10\Retenciones;
use PHPUnit\Framework\TestCase;

class RetencionesTest extends TestCase
{
    /** @var Retenciones */
    public $element;

    protected function setUp()
    {
        parent::setUp();
        $this->element = new Retenciones();
    }

    public function testGetElementName()
    {
        $this->assertSame('pago10:Retenciones', $this->element->getElementName());
    }

    public function testAddRetencion()
    {
        $parent = $this->element;

        // no childs
        $this->assertCount(0, $parent);

        // add first child
        $first = $this->element->addRetencion(['name' => 'first']);
        $this->assertInstanceOf(Retencion::class, $first);
        $this->assertSame('first', $first['name']);
        $this->assertCount(1, $this->element);

        // add second child
        $second = $this->element->addRetencion();
        $this->assertCount(2, $this->element);

        // test that first and second are not the same
        $this->assertNotSame($first, $second);
    }

    public function testMultiRetencion()
    {
        $node = $this->element;
        $this->assertCount(0, $node);
        $multiReturn = $node->multiRetencion(
            ['id' => 'first'],
            ['id' => 'second']
        );
        $this->assertSame($multiReturn, $node);
        $this->assertCount(2, $node);
        $this->assertSame('first', $node->searchAttribute('pago10:Retencion', 'id'));
    }
}
