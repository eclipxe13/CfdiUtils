<?php

namespace CfdiUtilsTests\Elements\CartaPorte10;

use CfdiUtils\Elements\CartaPorte10\Destino;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\CartaPorte10\Destino
 */
final class DestinoTest extends TestCase
{
    public Destino $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new Destino();
    }

    public function testConstructedObject(): void
    {
        $this->assertSame('cartaporte:Destino', $this->element->getElementName());
    }
}
