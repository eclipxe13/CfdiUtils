<?php

namespace CfdiUtilsTests\Elements\Cfdi33;

use CfdiUtils\Elements\Cfdi33\Receptor;
use PHPUnit\Framework\TestCase;

class ReceptorTest extends TestCase
{
    /** @var Receptor */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new Receptor();
    }

    public function testGetElementName()
    {
        $this->assertSame('cfdi:Receptor', $this->element->getElementName());
    }
}
