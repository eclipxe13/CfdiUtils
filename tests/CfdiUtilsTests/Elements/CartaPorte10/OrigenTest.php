<?php

namespace CfdiUtilsTests\Elements\CartaPorte10;

use CfdiUtils\Elements\CartaPorte10\Origen;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\CartaPorte10\Origen
 */
final class OrigenTest extends TestCase
{
    /** @var Origen */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new Origen();
    }

    public function testConstructedObject()
    {
        $this->assertSame('cartaporte:Origen', $this->element->getElementName());
    }
}
