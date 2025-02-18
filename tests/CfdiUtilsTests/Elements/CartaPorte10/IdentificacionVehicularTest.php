<?php

namespace CfdiUtilsTests\Elements\CartaPorte10;

use CfdiUtils\Elements\CartaPorte10\IdentificacionVehicular;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\CartaPorte10\IdentificacionVehicular
 */
final class IdentificacionVehicularTest extends TestCase
{
    /** @var IdentificacionVehicular */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new IdentificacionVehicular();
    }

    public function testConstructedObject(): void
    {
        $this->assertSame('cartaporte:IdentificacionVehicular', $this->element->getElementName());
    }
}
