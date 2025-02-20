<?php

namespace CfdiUtilsTests\Elements\CartaPorte10;

use CfdiUtils\Elements\CartaPorte10\TransporteAereo;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\CartaPorte10\TransporteAereo
 */
final class TransporteAereoTest extends TestCase
{
    public TransporteAereo $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new TransporteAereo();
    }

    public function testConstructedObject(): void
    {
        $this->assertSame('cartaporte:TransporteAereo', $this->element->getElementName());
    }
}
