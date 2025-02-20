<?php

namespace CfdiUtilsTests\Elements\Pagos10;

use CfdiUtils\Elements\Pagos10\Pago;
use CfdiUtils\Elements\Pagos10\Pagos;
use PHPUnit\Framework\TestCase;

final class PagosTest extends TestCase
{
    public Pagos $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new Pagos();
    }

    public function testConstructedObject(): void
    {
        $this->assertSame('pago10:Pagos', $this->element->getElementName());
    }

    public function testAddPago(): void
    {
        // object is empty
        $this->assertCount(0, $this->element);

        // add insert first element
        $first = $this->element->addPago(['id' => 'first']);
        $this->assertInstanceOf(Pago::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // add insert second element and is not the same
        $second = $this->element->addPago(['id' => 'second']);
        $this->assertSame('second', $second['id']);
        $this->assertCount(2, $this->element);
        $this->assertNotSame($first, $second);
    }

    public function testMultiPago(): void
    {
        $node = $this->element;
        $this->assertCount(0, $node);
        $multiReturn = $node->multiPago(
            ['id' => 'first'],
            ['id' => 'second']
        );
        $this->assertSame($multiReturn, $node);
        $this->assertCount(2, $node);
        $this->assertSame('first', $node->searchAttribute('pago10:Pago', 'id'));
    }
}
