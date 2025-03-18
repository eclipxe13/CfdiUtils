<?php

namespace CfdiUtilsTests\Elements\CartaPorte10;

use CfdiUtils\Elements\CartaPorte10\Domicilio;
use CfdiUtils\Elements\CartaPorte10\Notificado;
use CfdiUtilsTests\Elements\ElementTestCase;

/**
 * @covers \CfdiUtils\Elements\CartaPorte10\Notificado
 */
final class NotificadoTest extends ElementTestCase
{
    public Notificado $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new Notificado();
    }

    public function testConstructedObject(): void
    {
        $this->assertSame('cartaporte:Notificado', $this->element->getElementName());
    }

    public function testDomicilio(): void
    {
        $this->assertElementHasChildSingle($this->element, Domicilio::class);
    }
}
