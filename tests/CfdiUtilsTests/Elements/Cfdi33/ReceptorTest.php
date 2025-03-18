<?php

namespace CfdiUtilsTests\Elements\Cfdi33;

use CfdiUtils\Elements\Cfdi33\Receptor;
use PHPUnit\Framework\TestCase;

final class ReceptorTest extends TestCase
{
    public Receptor $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new Receptor();
    }

    public function testGetElementName(): void
    {
        $this->assertSame('cfdi:Receptor', $this->element->getElementName());
    }
}
