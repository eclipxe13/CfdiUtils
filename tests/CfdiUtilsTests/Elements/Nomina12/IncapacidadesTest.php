<?php

namespace CfdiUtilsTests\Elements\Nomina12;

use CfdiUtils\Elements\Nomina12\Incapacidad;
use CfdiUtils\Elements\Nomina12\Incapacidades;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\Nomina12\Incapacidades
 */
final class IncapacidadesTest extends TestCase
{
    /** @var Incapacidades */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new Incapacidades();
    }

    public function testConstructedObject()
    {
        $this->assertSame('nomina12:Incapacidades', $this->element->getElementName());
    }

    public function testAddIncapacidad()
    {
        // insert first element
        $first = $this->element->addIncapacidad(['id' => 'first']);
        $this->assertInstanceOf(Incapacidad::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return a different element
        $second = $this->element->addIncapacidad(['id' => 'second']);
        $this->assertNotEquals($first, $second);
        $this->assertCount(2, $this->element);
    }

    public function testMultiIncapacidad()
    {
        // insert first element
        $incapacidades = $this->element->multiIncapacidad(
            ['id' => 'first'],
            ['id' => 'second']
        );
        $this->assertCount(2, $incapacidades);
        $this->assertSame($this->element, $incapacidades);
    }
}
