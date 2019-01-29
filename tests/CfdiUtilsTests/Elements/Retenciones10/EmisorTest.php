<?php
namespace CfdiUtilsTests\Elements\Retenciones10;

use CfdiUtils\Elements\Retenciones10\Emisor;
use PHPUnit\Framework\TestCase;

class EmisorTest extends TestCase
{
    /** @var Emisor */
    public $element;

    public function setUp()
    {
        parent::setUp();
        $this->element = new Emisor();
    }

    public function testGetElementName()
    {
        $this->assertSame('retenciones:Emisor', $this->element->getElementName());
    }
}
