<?php

namespace CfdiUtilsTests\Elements\Cfdi33;

use CfdiUtils\Elements\Cfdi33\CfdiRelacionado;
use PHPUnit\Framework\TestCase;

final class CfdiRelacionadoTest extends TestCase
{
    public CfdiRelacionado $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new CfdiRelacionado();
    }

    public function testElementName(): void
    {
        $this->assertSame('cfdi:CfdiRelacionado', $this->element->getElementName());
    }
}
