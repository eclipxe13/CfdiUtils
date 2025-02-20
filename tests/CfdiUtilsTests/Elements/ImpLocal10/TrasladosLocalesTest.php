<?php

namespace CfdiUtilsTests\Elements\ImpLocal10;

use CfdiUtils\Elements\ImpLocal10\TrasladosLocales;
use PHPUnit\Framework\TestCase;

final class TrasladosLocalesTest extends TestCase
{
    public TrasladosLocales $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new TrasladosLocales();
    }

    public function testGetElementName(): void
    {
        $this->assertSame('implocal:TrasladosLocales', $this->element->getElementName());
    }
}
