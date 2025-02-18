<?php

namespace CfdiUtilsTests\Elements\Cfdi33;

use CfdiUtils\Elements\Cfdi33\Retencion;
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
        $this->assertSame('cfdi:Retencion', $this->element->getElementName());
    }
}
