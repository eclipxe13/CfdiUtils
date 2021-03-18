<?php

namespace CfdiUtilsTests\Elements\Cce11;

use CfdiUtils\Elements\Cce11\DescripcionesEspecificas;
use CfdiUtils\Elements\Cce11\Mercancia;
use PHPUnit\Framework\TestCase;

final class MercanciaTest extends TestCase
{
    /** @var Mercancia */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new Mercancia();
    }

    public function testConstructedObject()
    {
        $this->assertSame('cce11:Mercancia', $this->element->getElementName());
    }

    public function testDescripcionesEspecificas()
    {
        // object is empty
        $this->assertCount(0, $this->element);

        // add insert first element
        $first = $this->element->addDescripcionesEspecificas(['id' => 'first']);
        $this->assertInstanceOf(DescripcionesEspecificas::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // add insert second element and is not the same
        $second = $this->element->addDescripcionesEspecificas(['id' => 'second']);
        $this->assertSame('second', $second['id']);
        $this->assertCount(2, $this->element);
        $this->assertNotSame($first, $second);
    }
}
