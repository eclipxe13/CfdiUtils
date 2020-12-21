<?php

namespace CfdiUtilsTests\Elements\Nomina12;

use CfdiUtils\Elements\Nomina12\OtroPago;
use CfdiUtils\Elements\Nomina12\OtrosPagos;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\Nomina12\OtrosPagos
 */
class OtrosPagosTest extends TestCase
{
    /** @var OtrosPagos */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new OtrosPagos();
    }

    public function testConstructedObject()
    {
        $this->assertSame('nomina12:OtrosPagos', $this->element->getElementName());
    }

    public function testAddOtrosPago()
    {
        // insert first element
        $first = $this->element->addOtrosPago(['id' => 'first']);
        $this->assertInstanceOf(OtroPago::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return a different element
        $second = $this->element->addOtrosPago(['id' => 'second']);
        $this->assertNotEquals($first, $second);
        $this->assertCount(2, $this->element);
    }

    public function testMultiOtrosPago()
    {
        // insert first element
        $deducciones = $this->element->multiOtrosPago(
            ['id' => 'first'],
            ['id' => 'second']
        );
        $this->assertCount(2, $deducciones);
        $this->assertSame($this->element, $deducciones);
    }
}
