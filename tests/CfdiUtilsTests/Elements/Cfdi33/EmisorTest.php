<?php
namespace CfdiUtilsTests\Elements\Cfdi33;

use CfdiUtils\Elements\Cfdi33\Emisor;
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
        $this->assertSame('cfdi:Emisor', $this->element->getElementName());
    }
}
