<?php

namespace CfdiUtilsTests\Elements\Nomina12;

use CfdiUtils\Elements\Nomina12\Deduccion;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\Nomina12\Deduccion
 */
class DeduccionTest extends TestCase
{
    /** @var Deduccion */
    public $element;

    protected function setUp()
    {
        parent::setUp();
        $this->element = new Deduccion();
    }

    public function testConstructedObject()
    {
        $this->assertSame('nomina12:Deduccion', $this->element->getElementName());
    }
}
