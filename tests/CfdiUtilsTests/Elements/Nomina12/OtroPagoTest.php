<?php

namespace CfdiUtilsTests\Elements\Nomina12;

use CfdiUtils\Elements\Nomina12\CompensacionSaldosAFavor;
use CfdiUtils\Elements\Nomina12\OtroPago;
use CfdiUtils\Elements\Nomina12\SubsidioAlEmpleo;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\Nomina12\OtroPago
 */
final class OtroPagoTest extends TestCase
{
    public OtroPago $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new OtroPago();
    }

    public function testConstructedObject(): void
    {
        $this->assertSame('nomina12:OtroPago', $this->element->getElementName());
    }

    public function testChildrenOrder(): void
    {
        $expected = ['nomina12:SubsidioAlEmpleo', 'nomina12:CompensacionSaldosAFavor'];
        $this->assertSame($expected, $this->element->getChildrenOrder());
    }

    public function testGetSubsidioAlEmpleo(): void
    {
        $this->assertCount(0, $this->element->searchNodes('nomina12:SubsidioAlEmpleo'));

        $first = $this->element->getSubsidioAlEmpleo();
        $this->assertCount(1, $this->element->searchNodes('nomina12:SubsidioAlEmpleo'));

        $second = $this->element->getSubsidioAlEmpleo();
        $this->assertCount(1, $this->element->searchNodes('nomina12:SubsidioAlEmpleo'));

        $this->assertSame($first, $second);
    }

    public function testAddSubsidioAlEmpleo(): void
    {
        // insert first element
        $first = $this->element->addSubsidioAlEmpleo(['id' => 'first']);
        $this->assertInstanceOf(SubsidioAlEmpleo::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return the same element
        $second = $this->element->addSubsidioAlEmpleo(['id' => 'second']);
        $this->assertSame($first, $second);
        $this->assertSame('second', $first['id']);
    }

    public function testGetCompensacionSaldosAFavor(): void
    {
        $this->assertCount(0, $this->element->searchNodes('nomina12:CompensacionSaldosAFavor'));

        $first = $this->element->getCompensacionSaldosAFavor();
        $this->assertCount(1, $this->element->searchNodes('nomina12:CompensacionSaldosAFavor'));

        $second = $this->element->getCompensacionSaldosAFavor();
        $this->assertCount(1, $this->element->searchNodes('nomina12:CompensacionSaldosAFavor'));

        $this->assertSame($first, $second);
    }

    public function testAddCompensacionSaldosAFavor(): void
    {
        // insert first element
        $first = $this->element->addCompensacionSaldosAFavor(['id' => 'first']);
        $this->assertInstanceOf(CompensacionSaldosAFavor::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return the same element
        $second = $this->element->addCompensacionSaldosAFavor(['id' => 'second']);
        $this->assertSame($first, $second);
        $this->assertSame('second', $first['id']);
    }
}
