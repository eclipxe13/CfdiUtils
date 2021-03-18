<?php

namespace CfdiUtilsTests\Elements\Nomina12;

use CfdiUtils\Elements\Nomina12\EntidadSNCF;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\Nomina12\EntidadSNCF
 */
final class EntidadSNCFTest extends TestCase
{
    /** @var EntidadSNCF */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new EntidadSNCF();
    }

    public function testConstructedObject()
    {
        $this->assertSame('nomina12:EntidadSNCF', $this->element->getElementName());
    }
}
