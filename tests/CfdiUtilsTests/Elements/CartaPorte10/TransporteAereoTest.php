<?php

namespace CfdiUtilsTests\Elements\CartaPorte10;

use CfdiUtils\Elements\CartaPorte10\TransporteAereo;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\CartaPorte10\TransporteAereo
 */
final class TransporteAereoTest extends TestCase
{
    /** @var TransporteAereo */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new TransporteAereo();
    }

    public function testConstructedObject()
    {
        $this->assertSame('cartaporte:TransporteAereo', $this->element->getElementName());
    }
}
