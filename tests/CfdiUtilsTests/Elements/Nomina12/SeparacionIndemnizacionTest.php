<?php

namespace CfdiUtilsTests\Elements\Nomina12;

use CfdiUtils\Elements\Nomina12\SeparacionIndemnizacion;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\Nomina12\SeparacionIndemnizacion
 */
class SeparacionIndemnizacionTest extends TestCase
{
    /** @var SeparacionIndemnizacion */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new SeparacionIndemnizacion();
    }

    public function testConstructedObject()
    {
        $this->assertSame('nomina12:SeparacionIndemnizacion', $this->element->getElementName());
    }
}
