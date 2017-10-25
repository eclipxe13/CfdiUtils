<?php
namespace CfdiUtilsTests\Elements\Cfdi33;

use CfdiUtils\Elements\Cfdi33\Concepto;
use CfdiUtils\Elements\Cfdi33\CuentaPredial;
use PHPUnit\Framework\TestCase;

class ConceptoTest extends TestCase
{
    /** @var  Concepto */
    public $element;

    public function setUp()
    {
        parent::setUp();
        $this->element = new Concepto();
    }

    public function testGetCuentaPredial()
    {
        $this->assertNull($this->element->searchNode('cfdi:CuentaPredial'));
        $child = $this->element->getCuentaPredial();
        $this->assertInstanceOf(CuentaPredial::class, $child);
        $this->assertSame($child, $this->element->searchNode('cfdi:CuentaPredial'));
    }

    public function testAddCuentaPredial()
    {
        $parent = $this->element;
        $this->assertCount(0, $parent);
        $this->element->addCuentaPredial(['id' => 'first']);
        $this->assertCount(1, $parent);
        $this->assertSame('first', $parent->searchAttribute('cfdi:CuentaPredial', 'id'));
    }
}
