<?php

namespace CfdiUtilsTests\Elements\CartaPorte10;

use CfdiUtils\Elements\CartaPorte10\DetalleMercancia;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\CartaPorte10\DetalleMercancia
 */
final class DetalleMercanciaTest extends TestCase
{
    /** @var DetalleMercancia */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new DetalleMercancia();
    }

    public function testConstructedObject(): void
    {
        $this->assertSame('cartaporte:DetalleMercancia', $this->element->getElementName());
    }
}
