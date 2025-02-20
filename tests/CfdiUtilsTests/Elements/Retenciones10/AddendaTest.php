<?php

namespace CfdiUtilsTests\Elements\Retenciones10;

use CfdiUtils\Elements\Retenciones10\Addenda;
use CfdiUtils\Nodes\Node;
use PHPUnit\Framework\TestCase;

final class AddendaTest extends TestCase
{
    public Addenda $element;

    public function setUp(): void
    {
        parent::setUp();
        $this->element = new Addenda();
    }

    public function testGetElementName(): void
    {
        $this->assertSame('retenciones:Addenda', $this->element->getElementName());
    }

    public function testAdd(): void
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
