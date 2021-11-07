<?php

namespace CfdiUtilsTests\Elements\CartaPorte10;

use CfdiUtils\Elements\CartaPorte10\Domicilio;
use CfdiUtils\Elements\CartaPorte10\Propietario;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\CartaPorte10\Propietario
 */
final class PropietarioTest extends TestCase
{
    /** @var Propietario */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new Propietario();
    }

    public function testConstructedObject()
    {
        $this->assertSame('cartaporte:Propietario', $this->element->getElementName());
    }

    public function testGetDomicilio()
    {
        $this->assertCount(0, $this->element->searchNodes('cartaporte:Domicilio'));

        $first = $this->element->getDomicilio();
        $this->assertCount(1, $this->element->searchNodes('cartaporte:Domicilio'));

        $second = $this->element->getDomicilio();
        $this->assertCount(1, $this->element->searchNodes('cartaporte:Domicilio'));

        $this->assertSame($first, $second);
    }

    public function testAddDomicilio()
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
