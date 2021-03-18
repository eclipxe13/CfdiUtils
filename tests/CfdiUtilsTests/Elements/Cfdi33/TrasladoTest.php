<?php

namespace CfdiUtilsTests\Elements\Cfdi33;

use CfdiUtils\Elements\Cfdi33\Traslado;
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

    public function testGetElementName()
    {
        $this->assertSame('cfdi:Traslado', $this->element->getElementName());
    }
}
