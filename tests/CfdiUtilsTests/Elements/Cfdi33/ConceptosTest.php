<?php

namespace CfdiUtilsTests\Elements\Cfdi33;

use CfdiUtils\Elements\Cfdi33\Concepto;
use CfdiUtils\Elements\Cfdi33\Conceptos;
use PHPUnit\Framework\TestCase;

class ConceptosTest extends TestCase
{
    /** @var Conceptos */
    public $element;

    public function setUp(): void
    {
        parent::setUp();
        $this->element = new Conceptos();
    }

    public function testGetElementName()
    {
        $this->assertSame('cfdi:Conceptos', $this->element->getElementName());
    }

    public function testAddConcepto()
    {
        // no childs
        $parent = $this->element;
        $this->assertCount(0, $parent);

        // add first child
        $first = $parent->addConcepto(['name' => 'first']);
        $this->assertInstanceOf(Concepto::class, $first);
        $this->assertSame('first', $first['name']);
        $this->assertCount(1, $parent);

        // add second child
        $parent->addConcepto();
        $this->assertCount(2, $parent);
    }
}
