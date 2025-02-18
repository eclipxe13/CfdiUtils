<?php

namespace CfdiUtilsTests\Elements\Pagos10;

use CfdiUtils\Elements\Pagos10\Retencion;
use PHPUnit\Framework\TestCase;

final class RetencionTest extends TestCase
{
    /** @var Retencion */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new Retencion();
    }

    public function testGetElementName(): void
    {
        $this->assertSame('pago10:Retencion', $this->element->getElementName());
    }
}
