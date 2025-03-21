<?php

namespace CfdiUtilsTests\Elements\CartaPorte10;

use CfdiUtils\Elements\CartaPorte10\Remolque;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\CartaPorte10\Remolque
 */
final class RemolqueTest extends TestCase
{
    public Remolque $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new Remolque();
    }

    public function testConstructedObject(): void
    {
        $this->assertSame('cartaporte:Remolque', $this->element->getElementName());
    }
}
