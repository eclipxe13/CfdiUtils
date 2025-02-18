<?php

namespace CfdiUtilsTests\Elements\CartaPorte10;

use CfdiUtils\Elements\CartaPorte10\Contenedor;
use CfdiUtils\Elements\CartaPorte10\TransporteMaritimo;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\CartaPorte10\TransporteMaritimo
 */
final class TransporteMaritimoTest extends TestCase
{
    /** @var TransporteMaritimo */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new TransporteMaritimo();
    }

    public function testConstructedObject(): void
    {
        $this->assertSame('cartaporte:TransporteMaritimo', $this->element->getElementName());
    }

    public function testAddContenedor(): void
    {
        // insert first element
        $first = $this->element->addContenedor(['id' => 'first']);
        $this->assertInstanceOf(Contenedor::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return a different element
        $second = $this->element->addContenedor(['id' => 'second']);
        $this->assertNotEquals($first, $second);
        $this->assertCount(2, $this->element);
    }

    public function testMultiContenedor(): void
    {
        // insert first element
        $contenedores = $this->element->multiContenedor(
            ['id' => 'first'],
            ['id' => 'second']
        );
        $this->assertCount(2, $contenedores);
        $this->assertSame($this->element, $contenedores);
    }
}
