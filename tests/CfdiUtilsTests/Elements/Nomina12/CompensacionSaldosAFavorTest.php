<?php

namespace CfdiUtilsTests\Elements\Nomina12;

use CfdiUtils\Elements\Nomina12\CompensacionSaldosAFavor;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\Nomina12\CompensacionSaldosAFavor
 */
class CompensacionSaldosAFavorTest extends TestCase
{
    /** @var CompensacionSaldosAFavor */
    public $element;

    protected function setUp()
    {
        parent::setUp();
        $this->element = new CompensacionSaldosAFavor();
    }

    public function testConstructedObject()
    {
        $this->assertSame('nomina12:CompensacionSaldosAFavor', $this->element->getElementName());
    }
}
