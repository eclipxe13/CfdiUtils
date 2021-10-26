<?php

namespace CfdiUtilsTests\Elements\CartaPorte10;

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
}
