<?php

namespace CfdiUtilsTests\Elements\CartaPorte10;

use CfdiUtils\Elements\CartaPorte10\Notificado;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\CartaPorte10\Notificado
 */
final class NotificadoTest extends TestCase
{
    /** @var Notificado */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new Notificado();
    }

    public function testConstructedObject()
    {
        $this->assertSame('cartaporte:Notificado', $this->element->getElementName());
    }
}
