<?php

namespace CfdiUtilsTests\Elements\Nomina12;

use CfdiUtils\Elements\Nomina12\AccionesOTitulos;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\Nomina12\AccionesOTitulos
 */
final class AccionesOTitulosTest extends TestCase
{
    /** @var AccionesOTitulos */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new AccionesOTitulos();
    }

    public function testConstructedObject(): void
    {
        $this->assertSame('nomina12:AccionesOTitulos', $this->element->getElementName());
    }
}
