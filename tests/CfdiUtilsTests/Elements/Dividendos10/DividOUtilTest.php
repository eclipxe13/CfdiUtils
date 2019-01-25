<?php
namespace CfdiUtilsTests\Elements\Dividendos10;

use CfdiUtils\Elements\Dividendos10\DividOUtil;
use PHPUnit\Framework\TestCase;

class DividOUtilTest extends TestCase
{
    /** @var DividOUtil */
    public $element;

    public function setUp()
    {
        parent::setUp();
        $this->element = new DividOUtil();
    }

    public function testGetElementName()
    {
        $this->assertSame('dividendos:DividOUtil', $this->element->getElementName());
    }
}
