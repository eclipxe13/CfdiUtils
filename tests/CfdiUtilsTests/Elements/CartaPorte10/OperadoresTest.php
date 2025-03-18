<?php

namespace CfdiUtilsTests\Elements\CartaPorte10;

use CfdiUtils\Elements\CartaPorte10\Operador;
use CfdiUtils\Elements\CartaPorte10\Operadores;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\CartaPorte10\Operadores
 */
final class OperadoresTest extends TestCase
{
    public Operadores $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new Operadores();
    }

    public function testConstructedObject(): void
    {
        $this->assertSame('cartaporte:Operadores', $this->element->getElementName());
    }

    public function testAddOperador(): void
    {
        // insert first element
        $first = $this->element->addOperador(['id' => 'first']);
        $this->assertInstanceOf(Operador::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return a different element
        $second = $this->element->addOperador(['id' => 'second']);
        $this->assertNotEquals($first, $second);
        $this->assertCount(2, $this->element);
    }

    public function testMultiOperador(): void
    {
        // insert first element
        $operadores = $this->element->multiOperador(
            ['id' => 'first'],
            ['id' => 'second']
        );
        $this->assertCount(2, $operadores);
        $this->assertSame($this->element, $operadores);
    }
}
