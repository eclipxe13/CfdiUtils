<?php

namespace CfdiUtilsTests\Elements\Nomina12;

use CfdiUtils\Elements\Nomina12\JubilacionPensionRetiro;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\Nomina12\JubilacionPensionRetiro
 */
final class JubilacionPensionRetiroTest extends TestCase
{
    public JubilacionPensionRetiro $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new JubilacionPensionRetiro();
    }

    public function testConstructedObject(): void
    {
        $this->assertSame('nomina12:JubilacionPensionRetiro', $this->element->getElementName());
    }
}
