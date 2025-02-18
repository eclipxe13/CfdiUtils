<?php

namespace CfdiUtilsTests\Elements\Pagos10;

use CfdiUtils\Elements\Pagos10\Traslado;
use PHPUnit\Framework\TestCase;

final class TrasladoTest extends TestCase
{
    /** @var Traslado */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new Traslado();
    }

    public function testGetElementName(): void
    {
        $this->assertSame('pago10:Traslado', $this->element->getElementName());
    }
}
