<?php
namespace CfdiUtilsTests\Elements\ImpLocal10;

use CfdiUtils\Elements\ImpLocal10\TrasladosLocales;
use PHPUnit\Framework\TestCase;

class TrasladosLocalesTest extends TestCase
{
    /** @var TrasladosLocales */
    public $element;

    protected function setUp()
    {
        parent::setUp();
        $this->element = new TrasladosLocales();
    }

    public function testGetElementName()
    {
        $this->assertSame('implocal:TrasladosLocales', $this->element->getElementName());
    }
}
