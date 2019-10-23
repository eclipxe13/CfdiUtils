<?php

namespace CfdiUtilsTests\Elements\Retenciones10;

use CfdiUtils\Elements\Retenciones10\Complemento;
use CfdiUtils\Nodes\Node;
use PHPUnit\Framework\TestCase;

class ComplementoTest extends TestCase
{
    /** @var Complemento */
    public $element;

    public function setUp()
    {
        parent::setUp();
        $this->element = new Complemento();
    }

    public function testGetElementName()
    {
        $this->assertSame('retenciones:Complemento', $this->element->getElementName());
    }

    public function testAdd()
    {
        $this->assertCount(0, $this->element);

        $firstChild = new Node('first');
        $addReturn = $this->element->add($firstChild);
        $this->assertSame($this->element, $addReturn);
        $this->assertCount(1, $this->element);
        $this->assertSame($firstChild, $this->element->searchNode('first'));

        $secondChild = new Node('second');
        $this->element->add($secondChild);
        $this->assertCount(2, $this->element);
        $this->assertSame($secondChild, $this->element->searchNode('second'));
    }
}
