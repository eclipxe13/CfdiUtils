<?php
namespace CfdiUtilsTests\Nodes;

use CfdiUtils\Nodes\Node;
use CfdiUtils\Nodes\Nodes;
use CfdiUtilsTests\TestCase;

class NodesTest extends TestCase
{
    public function testEmptyNodes()
    {
        $nodes = new Nodes();
        $this->assertCount(0, $nodes);
        $this->assertNull($nodes->firstNodeWithName('non-existent'));
    }

    public function testConstructWithNodesArray()
    {
        $expected = [
            new Node('foo'),
            new Node('bar'),
        ];
        $nodes = new Nodes($expected);
        $this->assertCount(2, $nodes);
        foreach ($nodes as $index => $node) {
            $this->assertSame($expected[$index], $node);
        }
    }

    public function testManipulateTheCollection()
    {
        $first = new Node('first');
        $second = new Node('second');

        $nodes = new Nodes();
        $nodes->add($first, $second);

        $this->assertCount(2, $nodes);
        $this->assertTrue($nodes->exists($first));
        $this->assertTrue($nodes->exists($second));

        $equalToFirst = new Node('foo');
        $this->assertFalse($nodes->exists($equalToFirst));

        // add an equal node
        $nodes->add($equalToFirst);
        $this->assertCount(3, $nodes);

        // add an identical node
        $nodes->add($equalToFirst);
        $this->assertCount(3, $nodes);

        // remove the node
        $nodes->remove($equalToFirst);
        $this->assertCount(2, $nodes);

        // remove the node again
        $nodes->remove($equalToFirst);
        $this->assertCount(2, $nodes);

        $this->assertNull($nodes->firstNodeWithName('foo'));
        $this->assertSame($first, $nodes->firstNodeWithName('first'));
        $this->assertSame($second, $nodes->firstNodeWithName('second'));
    }

    public function testAddFindRemove()
    {
        $root = new Node('root');
        $nodes = $root->children();
        $child = new Node('child');

        $nodes->add($child);
        $this->assertTrue($nodes->exists($child));

        $found = $root->searchNode('child');
        $this->assertSame($child, $found);

        if (null !== $found) {
            $nodes->remove($found);
        }
        $this->assertFalse($nodes->exists($child));
    }

    public function testFirstReturnsNull()
    {
        $nodes = new Nodes();
        $this->assertNull($nodes->first());
    }

    public function testImportFromArray()
    {
        $nodeOne = new Node('one');
        $nodes = new Nodes();
        $nodes->importFromArray([
            $nodeOne,
            new Node('two'),
            new Node('three'),
        ]);
        $this->assertCount(3, $nodes);
        $this->assertSame($nodeOne, $nodes->first());
    }

    public function testImportFromArrayWithNonNode()
    {
        $nodes = new Nodes();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The element index 0 is not a Node class');
        $nodes->importFromArray([new \stdClass()]);
    }
}
