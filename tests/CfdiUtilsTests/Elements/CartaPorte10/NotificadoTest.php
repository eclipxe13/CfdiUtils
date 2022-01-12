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

    public function testDomicilio()
    {
        $this->assertElementHasChildSingle($this->element, Domicilio::class);
    }
}
