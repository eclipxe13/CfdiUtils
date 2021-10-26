<?php

namespace CfdiUtilsTests\Elements\CartaPorte10;

use CfdiUtils\Elements\CartaPorte10\Mercancias;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\CartaPorte10\Mercancias
 */
final class MercanciasTest extends TestCase
{
    /** @var Mercancias */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new Mercancias();
    }

    public function testConstructedObject()
    {
        $this->assertSame('cartaporte:Mercancias', $this->element->getElementName());
    }
}
