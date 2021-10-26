<?php

namespace CfdiUtilsTests\Elements\CartaPorte10;

use CfdiUtils\Elements\CartaPorte10\FiguraTransporte;
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
}
