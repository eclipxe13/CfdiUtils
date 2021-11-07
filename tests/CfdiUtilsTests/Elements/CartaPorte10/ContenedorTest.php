<?php

namespace CfdiUtilsTests\Elements\CartaPorte10;

use CfdiUtils\Elements\CartaPorte10\Contenedor;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\CartaPorte10\Contenedor
 */
final class ContenedorTest extends TestCase
{
    /** @var Contenedor */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new Contenedor();
    }

    public function testConstructedObject()
    {
        $this->assertSame('cartaporte:Contenedor', $this->element->getElementName());
    }
}
