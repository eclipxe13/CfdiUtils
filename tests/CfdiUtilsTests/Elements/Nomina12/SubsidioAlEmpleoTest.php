<?php

namespace CfdiUtilsTests\Elements\Nomina12;

use CfdiUtils\Elements\Nomina12\SubsidioAlEmpleo;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\Nomina12\SubsidioAlEmpleo
 */
class SubsidioAlEmpleoTest extends TestCase
{
    /** @var SubsidioAlEmpleo */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new SubsidioAlEmpleo();
    }

    public function testConstructedObject()
    {
        $this->assertSame('nomina12:SubsidioAlEmpleo', $this->element->getElementName());
    }
}
