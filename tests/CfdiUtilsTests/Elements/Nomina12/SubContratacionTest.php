<?php

namespace CfdiUtilsTests\Elements\Nomina12;

use CfdiUtils\Elements\Nomina12\SubContratacion;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\Nomina12\SubContratacion
 */
final class SubContratacionTest extends TestCase
{
    public SubContratacion $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new SubContratacion();
    }

    public function testConstructedObject(): void
    {
        $this->assertSame('nomina12:SubContratacion', $this->element->getElementName());
    }
}
