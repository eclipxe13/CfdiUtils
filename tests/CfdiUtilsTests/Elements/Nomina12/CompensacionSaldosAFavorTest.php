<?php

namespace CfdiUtilsTests\Elements\Nomina12;

use CfdiUtils\Elements\Nomina12\CompensacionSaldosAFavor;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\Nomina12\CompensacionSaldosAFavor
 */
final class CompensacionSaldosAFavorTest extends TestCase
{
    /** @var CompensacionSaldosAFavor */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new CompensacionSaldosAFavor();
    }

    public function testConstructedObject(): void
    {
        $this->assertSame('nomina12:CompensacionSaldosAFavor', $this->element->getElementName());
    }
}
