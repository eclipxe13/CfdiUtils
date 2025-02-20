<?php

namespace CfdiUtilsTests\Elements\Nomina12;

use CfdiUtils\Elements\Nomina12\SeparacionIndemnizacion;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\Nomina12\SeparacionIndemnizacion
 */
final class SeparacionIndemnizacionTest extends TestCase
{
    public SeparacionIndemnizacion $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new SeparacionIndemnizacion();
    }

    public function testConstructedObject(): void
    {
        $this->assertSame('nomina12:SeparacionIndemnizacion', $this->element->getElementName());
    }
}
