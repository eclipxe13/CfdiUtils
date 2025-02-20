<?php

namespace CfdiUtilsTests\Elements\Dividendos10;

use CfdiUtils\Elements\Dividendos10\DividOUtil;
use PHPUnit\Framework\TestCase;

final class DividOUtilTest extends TestCase
{
    public DividOUtil $element;

    public function setUp(): void
    {
        parent::setUp();
        $this->element = new DividOUtil();
    }

    public function testGetElementName(): void
    {
        $this->assertSame('dividendos:DividOUtil', $this->element->getElementName());
    }
}
