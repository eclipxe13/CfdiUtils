<?php

namespace CfdiUtilsTests\Elements\CartaPorte10;

use CfdiUtils\Elements\CartaPorte10\DerechosDePaso;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\CartaPorte10\DerechosDePaso
 */
final class DerechosDePasoTest extends TestCase
{
    /** @var DerechosDePaso */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new DerechosDePaso();
    }

    public function testConstructedObject()
    {
        $this->assertSame('cartaporte:DerechosDePaso', $this->element->getElementName());
    }
}
