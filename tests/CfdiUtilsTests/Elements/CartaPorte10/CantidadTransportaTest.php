<?php

namespace CfdiUtilsTests\Elements\CartaPorte10;

use CfdiUtils\Elements\CartaPorte10\CantidadTransporta;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\CartaPorte10\CantidadTransporta
 */
final class CantidadTransportaTest extends TestCase
{
    /** @var CantidadTransporta */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new CantidadTransporta();
    }

    public function testConstructedObject(): void
    {
        $this->assertSame('cartaporte:CantidadTransporta', $this->element->getElementName());
    }
}
