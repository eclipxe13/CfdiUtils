<?php
namespace CfdiUtilsTests\Elements\Cfdi33;

use CfdiUtils\Elements\Cfdi33\CfdiRelacionado;
use CfdiUtils\Elements\Cfdi33\CfdiRelacionados;
use CfdiUtilsTests\TestCase;

class CfdiRelacionadosTest extends TestCase
{
    /** @var CfdiRelacionados */
    public $element;

    protected function setUp()
    {
        parent::setUp();
        $this->element = new CfdiRelacionados();
    }

    public function testElementName()
    {
        $this->assertSame('cfdi:CfdiRelacionados', $this->element->getElementName());
    }

    public function testAddCfdiRelacionado()
    {
        // no childs
        $this->assertCount(0, $this->element);

        // add first child
        $first = $this->element->addCfdiRelacionado(['name' => 'first']);
        $this->assertInstanceOf(CfdiRelacionado::class, $first);
        $this->assertSame('first', $first['name']);
        $this->assertCount(1, $this->element);

        // add second child
        $this->element->addCfdiRelacionado();
        $this->assertCount(2, $this->element);
    }
}
