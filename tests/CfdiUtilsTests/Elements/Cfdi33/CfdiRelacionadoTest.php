<?php
namespace CfdiUtilsTests\Elements\Cfdi33;

use CfdiUtils\Elements\Cfdi33\CfdiRelacionado;
use PHPUnit\Framework\TestCase;

class CfdiRelacionadoTest extends TestCase
{
    /** @var CfdiRelacionado */
    public $element;

    protected function setUp()
    {
        parent::setUp();
        $this->element = new CfdiRelacionado();
    }

    public function testElementName()
    {
        $this->assertSame('cfdi:CfdiRelacionado', $this->element->getElementName());
    }
}
