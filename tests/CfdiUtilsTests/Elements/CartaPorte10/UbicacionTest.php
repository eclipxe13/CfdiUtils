<?php

namespace CfdiUtilsTests\Elements\CartaPorte10;

use CfdiUtils\Elements\CartaPorte10\Destino;
use CfdiUtils\Elements\CartaPorte10\Domicilio;
use CfdiUtils\Elements\CartaPorte10\Origen;
use CfdiUtils\Elements\CartaPorte10\Ubicacion;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\CartaPorte10\Ubicacion
 */
final class UbicacionTest extends TestCase
{
    /** @var Ubicacion */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new Ubicacion();
    }

    public function testConstructedObject(): void
    {
        $this->assertSame('cartaporte:Ubicacion', $this->element->getElementName());
    }

    public function testChildrenOrder(): void
    {
        $expected = ['cartaporte:Origen', 'cartaporte:Destino', 'cartaporte:Domicilio'];
        $this->assertSame($expected, $this->element->getChildrenOrder());
    }

    public function testGetOrigen(): void
    {
        $this->assertCount(0, $this->element->searchNodes('cartaporte:Origen'));

        $first = $this->element->getOrigen();
        $this->assertCount(1, $this->element->searchNodes('cartaporte:Origen'));

        $second = $this->element->getOrigen();
        $this->assertCount(1, $this->element->searchNodes('cartaporte:Origen'));

        $this->assertSame($first, $second);
    }

    public function testAddOrigen(): void
    {
        // insert first element
        $first = $this->element->addOrigen(['id' => 'first']);
        $this->assertInstanceOf(Origen::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return the same element
        $second = $this->element->addOrigen(['id' => 'second']);
        $this->assertSame($first, $second);
        $this->assertSame('second', $first['id']);
    }

    public function testGetDestino(): void
    {
        $this->assertCount(0, $this->element->searchNodes('cartaporte:Destino'));

        $first = $this->element->getDestino();
        $this->assertCount(1, $this->element->searchNodes('cartaporte:Destino'));

        $second = $this->element->getDestino();
        $this->assertCount(1, $this->element->searchNodes('cartaporte:Destino'));

        $this->assertSame($first, $second);
    }

    public function testAddDestino(): void
    {
        // insert first element
        $first = $this->element->addDestino(['id' => 'first']);
        $this->assertInstanceOf(Destino::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return the same element
        $second = $this->element->addDestino(['id' => 'second']);
        $this->assertSame($first, $second);
        $this->assertSame('second', $first['id']);
    }

    public function testGetDomicilio(): void
    {
        $this->assertCount(0, $this->element->searchNodes('cartaporte:Domicilio'));

        $first = $this->element->getDomicilio();
        $this->assertCount(1, $this->element->searchNodes('cartaporte:Domicilio'));

        $second = $this->element->getDomicilio();
        $this->assertCount(1, $this->element->searchNodes('cartaporte:Domicilio'));

        $this->assertSame($first, $second);
    }

    public function testAddDomicilio(): void
    {
        // insert first element
        $first = $this->element->addDomicilio(['id' => 'first']);
        $this->assertInstanceOf(Domicilio::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return the same element
        $second = $this->element->addDomicilio(['id' => 'second']);
        $this->assertSame($first, $second);
        $this->assertSame('second', $first['id']);
    }
}
