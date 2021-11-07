<?php

namespace CfdiUtilsTests\Elements\CartaPorte10;

use CfdiUtils\Elements\CartaPorte10\Ubicacion;
use CfdiUtils\Elements\CartaPorte10\Ubicaciones;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\CartaPorte10\Ubicaciones
 */
final class UbicacionesTest extends TestCase
{
    /** @var Ubicaciones */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new Ubicaciones();
    }

    public function testConstructedObject()
    {
        $this->assertSame('cartaporte:Ubicaciones', $this->element->getElementName());
    }

    public function testAddUbicacion()
    {
        // insert first element
        $first = $this->element->addUbicacion(['id' => 'first']);
        $this->assertInstanceOf(Ubicacion::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return a different element
        $second = $this->element->addUbicacion(['id' => 'second']);
        $this->assertNotEquals($first, $second);
        $this->assertCount(2, $this->element);
    }

    public function testMultiUbicacion()
    {
        // insert first element
        $ubicaciones = $this->element->multiUbicacion(
            ['id' => 'first'],
            ['id' => 'second']
        );
        $this->assertCount(2, $ubicaciones);
        $this->assertSame($this->element, $ubicaciones);
    }
}
