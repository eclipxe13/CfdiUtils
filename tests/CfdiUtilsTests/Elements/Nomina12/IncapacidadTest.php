<?php

namespace CfdiUtilsTests\Elements\Nomina12;

use CfdiUtils\Elements\Nomina12\Incapacidad;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\Nomina12\Incapacidad
 */
final class IncapacidadTest extends TestCase
{
    public Incapacidad $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new Incapacidad();
    }

    public function testConstructedObject(): void
    {
        $this->assertSame('nomina12:Incapacidad', $this->element->getElementName());
    }
}
