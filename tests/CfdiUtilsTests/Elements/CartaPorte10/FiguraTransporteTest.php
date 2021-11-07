<?php

namespace CfdiUtilsTests\Elements\CartaPorte10;

use CfdiUtils\Elements\CartaPorte10\Arrendatario;
use CfdiUtils\Elements\CartaPorte10\FiguraTransporte;
use CfdiUtils\Elements\CartaPorte10\Notificado;
use CfdiUtils\Elements\CartaPorte10\Operadores;
use CfdiUtils\Elements\CartaPorte10\Propietario;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\CartaPorte10\FiguraTransporte
 */
final class FiguraTransporteTest extends TestCase
{
    /** @var FiguraTransporte */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new FiguraTransporte();
    }

    public function testConstructedObject()
    {
        $this->assertSame('cartaporte:FiguraTransporte', $this->element->getElementName());
    }

    public function testAddOperadores()
    {
        // insert first element
        $first = $this->element->addOperadores(['id' => 'first']);
        $this->assertInstanceOf(Operadores::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return a different element
        $second = $this->element->addOperadores(['id' => 'second']);
        $this->assertNotEquals($first, $second);
        $this->assertCount(2, $this->element);
    }

    public function testMultiOperadores()
    {
        // insert first element
        $operadores = $this->element->multiOperadores(
            ['id' => 'first'],
            ['id' => 'second']
        );
        $this->assertCount(2, $operadores);
        $this->assertSame($this->element, $operadores);
    }

    public function testAddPropietario()
    {
        // insert first element
        $first = $this->element->addPropietario(['id' => 'first']);
        $this->assertInstanceOf(Propietario::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return a different element
        $second = $this->element->addPropietario(['id' => 'second']);
        $this->assertNotEquals($first, $second);
        $this->assertCount(2, $this->element);
    }

    public function testMultiPropietario()
    {
        // insert first element
        $propietario = $this->element->multiPropietario(
            ['id' => 'first'],
            ['id' => 'second']
        );
        $this->assertCount(2, $propietario);
        $this->assertSame($this->element, $propietario);
    }

    public function testAddArrendatario()
    {
        // insert first element
        $first = $this->element->addArrendatario(['id' => 'first']);
        $this->assertInstanceOf(Arrendatario::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return a different element
        $second = $this->element->addArrendatario(['id' => 'second']);
        $this->assertNotEquals($first, $second);
        $this->assertCount(2, $this->element);
    }

    public function testMultiArrendatario()
    {
        // insert first element
        $arrendatario = $this->element->multiArrendatario(
            ['id' => 'first'],
            ['id' => 'second']
        );
        $this->assertCount(2, $arrendatario);
        $this->assertSame($this->element, $arrendatario);
    }

    public function testAddNotificado()
    {
        // insert first element
        $first = $this->element->addNotificado(['id' => 'first']);
        $this->assertInstanceOf(Notificado::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return a different element
        $second = $this->element->addNotificado(['id' => 'second']);
        $this->assertNotEquals($first, $second);
        $this->assertCount(2, $this->element);
    }

    public function testMultiNotificado()
    {
        // insert first element
        $notificado = $this->element->multiNotificado(
            ['id' => 'first'],
            ['id' => 'second']
        );
        $this->assertCount(2, $notificado);
        $this->assertSame($this->element, $notificado);
    }
}
