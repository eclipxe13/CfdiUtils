<?php

namespace CfdiUtilsTests\Elements\CartaPorte10;

use CfdiUtils\Elements\CartaPorte10\Carro;
use CfdiUtils\Elements\CartaPorte10\DerechosDePaso;
use CfdiUtils\Elements\CartaPorte10\TransporteFerroviario;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\CartaPorte10\TransporteFerroviario
 */
final class TransporteFerroviarioTest extends TestCase
{
    public TransporteFerroviario $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new TransporteFerroviario();
    }

    public function testConstructedObject(): void
    {
        $this->assertSame('cartaporte:TransporteFerroviario', $this->element->getElementName());
    }

    public function testAddDerechosDePaso(): void
    {
        // insert first element
        $first = $this->element->addDerechosDePaso(['id' => 'first']);
        $this->assertInstanceOf(DerechosDePaso::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return a different element
        $second = $this->element->addDerechosDePaso(['id' => 'second']);
        $this->assertNotEquals($first, $second);
        $this->assertCount(2, $this->element);
    }

    public function testMultiDerechosDePaso(): void
    {
        // insert first element
        $derechosDePaso = $this->element->multiDerechosDePaso(
            ['id' => 'first'],
            ['id' => 'second']
        );
        $this->assertCount(2, $derechosDePaso);
        $this->assertSame($this->element, $derechosDePaso);
    }

    public function testAddCarro(): void
    {
        // insert first element
        $first = $this->element->addCarro(['id' => 'first']);
        $this->assertInstanceOf(Carro::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return a different element
        $second = $this->element->addCarro(['id' => 'second']);
        $this->assertNotEquals($first, $second);
        $this->assertCount(2, $this->element);
    }

    public function testMultiCarro(): void
    {
        // insert first element
        $carros = $this->element->multiCarro(
            ['id' => 'first'],
            ['id' => 'second']
        );
        $this->assertCount(2, $carros);
        $this->assertSame($this->element, $carros);
    }
}
