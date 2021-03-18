<?php

namespace CfdiUtilsTests\Elements\Cfdi33;

use CfdiUtils\Elements\Cfdi33\Parte;
use PHPUnit\Framework\TestCase;

final class ParteTest extends TestCase
{
    /** @var Parte */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new Parte();
    }

    public function testElementName()
    {
        $this->assertSame('cfdi:Parte', $this->element->getElementName());
    }
}
