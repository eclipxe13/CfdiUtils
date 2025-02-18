<?php

namespace CfdiUtilsTests\Elements\Nomina12;

use CfdiUtils\Elements\Nomina12\Deduccion;
use CfdiUtils\Elements\Nomina12\Deducciones;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\Nomina12\Deducciones
 */
final class DeduccionesTest extends TestCase
{
    /** @var Deducciones */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new Deducciones();
    }

    public function testConstructedObject(): void
    {
        $this->assertSame('nomina12:Deducciones', $this->element->getElementName());
    }

    public function testAddDeduccion(): void
    {
        // insert first element
        $first = $this->element->addDeduccion(['id' => 'first']);
        $this->assertInstanceOf(Deduccion::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return a different element
        $second = $this->element->addDeduccion(['id' => 'second']);
        $this->assertNotEquals($first, $second);
        $this->assertCount(2, $this->element);
    }

    public function testMultiDeduccion(): void
    {
        // insert first element
        $deducciones = $this->element->multiDeduccion(
            ['id' => 'first'],
            ['id' => 'second']
        );
        $this->assertCount(2, $deducciones);
        $this->assertSame($this->element, $deducciones);
    }
}
