<?php

namespace CfdiUtilsTests\Elements\Retenciones10;

use CfdiUtils\Elements\Retenciones10\Periodo;
use PHPUnit\Framework\TestCase;

final class PeriodoTest extends TestCase
{
    public Periodo $element;

    public function setUp(): void
    {
        parent::setUp();
        $this->element = new Periodo();
    }

    public function testGetElementName(): void
    {
        $this->assertSame('retenciones:Periodo', $this->element->getElementName());
    }
}
