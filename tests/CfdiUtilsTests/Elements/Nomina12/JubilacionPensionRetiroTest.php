<?php

namespace CfdiUtilsTests\Elements\Nomina12;

use CfdiUtils\Elements\Nomina12\JubilacionPensionRetiro;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\Nomina12\JubilacionPensionRetiro
 */
class JubilacionPensionRetiroTest extends TestCase
{
    /** @var JubilacionPensionRetiro */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new JubilacionPensionRetiro();
    }

    public function testConstructedObject()
    {
        $this->assertSame('nomina12:JubilacionPensionRetiro', $this->element->getElementName());
    }
}
