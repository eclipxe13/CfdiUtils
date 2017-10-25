<?php
namespace CfdiUtilsTests\Elements\Cfdi33;

use CfdiUtils\Elements\Cfdi33\Retencion;
use PHPUnit\Framework\TestCase;

class RetencionTest extends TestCase
{
    /** @var Retencion */
    public $element;

    protected function setUp()
    {
        parent::setUp();
        $this->element = new Retencion();
    }

    public function testGetElementName()
    {
        $this->assertSame('cfdi:Retencion', $this->element->getElementName());
    }
}
