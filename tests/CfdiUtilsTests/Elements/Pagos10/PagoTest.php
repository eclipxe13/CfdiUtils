<?php

namespace CfdiUtilsTests\Elements\Pagos10;

use CfdiUtils\Elements\Pagos10\DoctoRelacionado;
use CfdiUtils\Elements\Pagos10\Impuestos;
use CfdiUtils\Elements\Pagos10\Pago;
use PHPUnit\Framework\TestCase;

final class PagoTest extends TestCase
{
    /** @var Pago */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new Pago();
    }

    public function testConstructedObject()
    {
        $this->assertSame('pago10:Pago', $this->element->getElementName());
    }

    public function testDoctoRelacionado()
    {
        // object is empty
        $this->assertCount(0, $this->element);

        // add insert first element
        $first = $this->element->addDoctoRelacionado(['id' => 'first']);
        $this->assertInstanceOf(DoctoRelacionado::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // add insert second element and is not the same
        $second = $this->element->addDoctoRelacionado(['id' => 'second']);
        $this->assertSame('second', $second['id']);
        $this->assertCount(2, $this->element);
        $this->assertNotSame($first, $second);
    }

    public function testImpuestos()
    {
        // object is empty
        $this->assertCount(0, $this->element);

        // add insert first element
        $first = $this->element->addImpuestos(['id' => 'first']);
        $this->assertInstanceOf(Impuestos::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // add insert second element and is not the same
        $second = $this->element->addImpuestos(['id' => 'second']);
        $this->assertSame('second', $second['id']);
        $this->assertCount(2, $this->element);
        $this->assertNotSame($first, $second);
    }

    public function testChildrenOrder()
    {
        // add in inverse order
        $this->element->addImpuestos();
        $this->element->addDoctoRelacionado();

        // retrieve in correct order
        $this->assertInstanceOf(DoctoRelacionado::class, $this->element->children()->get(0));
        $this->assertInstanceOf(Impuestos::class, $this->element->children()->get(1));
    }
}
