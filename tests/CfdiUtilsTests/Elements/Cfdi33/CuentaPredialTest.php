<?php

namespace CfdiUtilsTests\Elements\Cfdi33;

use CfdiUtils\Elements\Cfdi33\CuentaPredial;
use PHPUnit\Framework\TestCase;

final class CuentaPredialTest extends TestCase
{
    public CuentaPredial $element;

    public function setUp(): void
    {
        parent::setUp();
        $this->element = new CuentaPredial();
    }

    public function testGetElementName(): void
    {
        $this->assertSame('cfdi:CuentaPredial', $this->element->getElementName());
    }
}
