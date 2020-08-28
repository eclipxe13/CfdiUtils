<?php

namespace CfdiUtilsTests\Elements\Nomina12;

use CfdiUtils\Elements\Nomina12\Incapacidad;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\Nomina12\Incapacidad
 */
class IncapacidadTest extends TestCase
{
    /** @var Incapacidad */
    public $element;

    protected function setUp()
    {
        parent::setUp();
        $this->element = new Incapacidad();
    }

    public function testConstructedObject()
    {
        $this->assertSame('nomina12:Incapacidad', $this->element->getElementName());
    }
}
