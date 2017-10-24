<?php
namespace CfdiUtilsTests\Elements\Cfdi33;

use CfdiUtils\Elements\Cfdi33\InformacionAduanera;
use CfdiUtils\Elements\Cfdi33\Parte;
use CfdiUtilsTests\TestCase;

class ParteTest extends TestCase
{
    /** @var Parte */
    public $element;

    protected function setUp()
    {
        parent::setUp();
        $this->element = new Parte();
    }

    public function testElementName()
    {
        $this->assertSame('cfdi:Parte', $this->element->getElementName());
    }

    public function testAddInformacionAduanera()
    {
        // no childs
        $this->assertCount(0, $this->element);

        // add first child
        $first = $this->element->addInformacionAduanera(['name' => 'first']);
        $this->assertInstanceOf(InformacionAduanera::class, $first);
        $this->assertSame('first', $first['name']);
        $this->assertCount(1, $this->element);

        // add second child
        $this->element->addInformacionAduanera();
        $this->assertCount(2, $this->element);
    }
}
