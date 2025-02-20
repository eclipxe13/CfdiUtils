<?php

namespace CfdiUtilsTests\Elements\CartaPorte10;

use CfdiUtils\Elements\CartaPorte10\Domicilio;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\CartaPorte10\Domicilio
 */
final class DomicilioTest extends TestCase
{
    public Domicilio $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new Domicilio();
    }

    public function testConstructedObject(): void
    {
        $this->assertSame('cartaporte:Domicilio', $this->element->getElementName());
    }
}
