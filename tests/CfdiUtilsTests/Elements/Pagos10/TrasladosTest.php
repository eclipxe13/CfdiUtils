<?php

namespace CfdiUtilsTests\Elements\Pagos10;

use CfdiUtils\Elements\Pagos10\Traslado;
use CfdiUtils\Elements\Pagos10\Traslados;
use PHPUnit\Framework\TestCase;

final class TrasladosTest extends TestCase
{
    /** @var Traslados */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new Traslados();
    }

    public function testGetElementName(): void
    {
        $this->assertSame('pago10:Traslados', $this->element->getElementName());
    }

    public function testAddTraslado(): void
    {
        $parent = $this->element;

        // no childs
        $this->assertCount(0, $parent);

        // add first child
        $first = $this->element->addTraslado(['name' => 'first']);
        $this->assertInstanceOf(Traslado::class, $first);
        $this->assertSame('first', $first['name']);
        $this->assertCount(1, $this->element);

        // add second child
        $second = $this->element->addTraslado();
        $this->assertCount(2, $this->element);

        // test that first and second are not the same
        $this->assertNotSame($first, $second);
    }

    public function testMultiTraslado(): void
    {
        $node = $this->element;
        $this->assertCount(0, $node);
        $multiReturn = $node->multiTraslado(
            ['id' => 'first'],
            ['id' => 'second']
        );
        $this->assertSame($multiReturn, $node);
        $this->assertCount(2, $node);
        $this->assertSame('first', $node->searchAttribute('pago10:Traslado', 'id'));
    }
}
