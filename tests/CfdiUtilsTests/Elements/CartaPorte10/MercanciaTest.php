<?php

namespace CfdiUtilsTests\Elements\CartaPorte10;

use CfdiUtils\Elements\CartaPorte10\CantidadTransporta;
use CfdiUtils\Elements\CartaPorte10\DetalleMercancia;
use CfdiUtils\Elements\CartaPorte10\Mercancia;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\CartaPorte10\Mercancia
 */
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
        $this->assertSame('cartaporte:Mercancia', $this->element->getElementName());
    }

    public function testAddCantidadTransporta()
    {
        // insert first element
        $first = $this->element->addCantidadTransporta(['id' => 'first']);
        $this->assertInstanceOf(CantidadTransporta::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return a different element
        $second = $this->element->addCantidadTransporta(['id' => 'second']);
        $this->assertNotEquals($first, $second);
        $this->assertCount(2, $this->element);
    }

    public function testMultiCantidadTransporta()
    {
        // insert first element
        $cantidadTransportaNodes = $this->element->multiCantidadTransporta(
            ['id' => 'first'],
            ['id' => 'second']
        );
        $this->assertCount(2, $cantidadTransportaNodes);
        $this->assertSame($this->element, $cantidadTransportaNodes);
    }

    public function testGetDetalleMercancia()
    {
        $this->assertCount(0, $this->element->searchNodes('cartaporte:DetalleMercancia'));

        $first = $this->element->getDetalleMercancia();
        $this->assertCount(1, $this->element->searchNodes('cartaporte:DetalleMercancia'));

        $second = $this->element->getDetalleMercancia();
        $this->assertCount(1, $this->element->searchNodes('cartaporte:DetalleMercancia'));

        $this->assertSame($first, $second);
    }

    public function testAddDetalleMercancia()
    {
        // insert first element
        $first = $this->element->addDetalleMercancia(['id' => 'first']);
        $this->assertInstanceOf(DetalleMercancia::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return a different element
        $second = $this->element->addDetalleMercancia(['id' => 'second']);
        $this->assertNotEquals($first, $second);
        $this->assertCount(2, $this->element);
    }
}
