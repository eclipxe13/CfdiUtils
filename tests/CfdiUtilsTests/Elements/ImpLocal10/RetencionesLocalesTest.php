<?php

namespace CfdiUtilsTests\Elements\ImpLocal10;

use CfdiUtils\Elements\ImpLocal10\RetencionesLocales;
use PHPUnit\Framework\TestCase;

final class RetencionesLocalesTest extends TestCase
{
    public RetencionesLocales $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new RetencionesLocales();
    }

    public function testGetElementName(): void
    {
        $this->assertSame('implocal:RetencionesLocales', $this->element->getElementName());
    }
}
