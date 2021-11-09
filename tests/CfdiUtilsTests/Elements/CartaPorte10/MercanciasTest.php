<?php

namespace CfdiUtilsTests\Elements\CartaPorte10;

use CfdiUtils\Elements\CartaPorte10\AutotransporteFederal;
use CfdiUtils\Elements\CartaPorte10\Mercancia;
use CfdiUtils\Elements\CartaPorte10\Mercancias;
use CfdiUtils\Elements\CartaPorte10\TransporteAereo;
use CfdiUtils\Elements\CartaPorte10\TransporteFerroviario;
use CfdiUtils\Elements\CartaPorte10\TransporteMaritimo;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\CartaPorte10\Mercancias
 */
final class MercanciasTest extends TestCase
{
    /** @var Mercancias */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new Mercancias();
    }

    public function testConstructedObject()
    {
        $this->assertSame('cartaporte:Mercancias', $this->element->getElementName());
    }

    public function testAddMercancia()
    {
        // insert first element
        $first = $this->element->addMercancia(['id' => 'first']);
        $this->assertInstanceOf(Mercancia::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return a different element
        $second = $this->element->addMercancia(['id' => 'second']);
        $this->assertNotEquals($first, $second);
        $this->assertCount(2, $this->element);
    }

    public function testMultiMercancia()
    {
        // insert first element
        $mercancias = $this->element->multiMercancia(
            ['id' => 'first'],
            ['id' => 'second']
        );
        $this->assertCount(2, $mercancias);
        $this->assertSame($this->element, $mercancias);
    }

    public function testAddAutotransporteFederal()
    {
        // insert first element
        $first = $this->element->addAutotransporteFederal(['id' => 'first']);
        $this->assertInstanceOf(AutotransporteFederal::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return a different element
        $second = $this->element->addAutotransporteFederal(['id' => 'second']);
        $this->assertNotEquals($first, $second);
        $this->assertCount(2, $this->element);
    }

    public function testAddTransporteMaritimo()
    {
        // insert first element
        $first = $this->element->addTransporteMaritimo(['id' => 'first']);
        $this->assertInstanceOf(TransporteMaritimo::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return a different element
        $second = $this->element->addTransporteMaritimo(['id' => 'second']);
        $this->assertNotEquals($first, $second);
        $this->assertCount(2, $this->element);
    }

    public function testAddTransporteAereo()
    {
        // insert first element
        $first = $this->element->addTransporteAereo(['id' => 'first']);
        $this->assertInstanceOf(TransporteAereo::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return a different element
        $second = $this->element->addTransporteAereo(['id' => 'second']);
        $this->assertNotEquals($first, $second);
        $this->assertCount(2, $this->element);
    }

    public function testAddTransporteFerroviario()
    {
        // insert first element
        $first = $this->element->addTransporteFerroviario(['id' => 'first']);
        $this->assertInstanceOf(TransporteFerroviario::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return a different element
        $second = $this->element->addTransporteFerroviario(['id' => 'second']);
        $this->assertNotEquals($first, $second);
        $this->assertCount(2, $this->element);
    }
}
