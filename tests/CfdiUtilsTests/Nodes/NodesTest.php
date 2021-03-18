<?php

namespace CfdiUtilsTests\Nodes;

use CfdiUtils\Nodes\Node;
use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Nodes\Nodes;
use CfdiUtilsTests\TestCase;

final class NodesTest extends TestCase
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
        $this->expectExceptionMessage('The element index 0 is not a NodeInterface object');
        /** @var NodeInterface $specimen Override type to avoid problems with static analyser */
        $specimen = new \stdClass();
        $nodes->importFromArray([$specimen]);
    }

    public function testGetThrowsExceptionWhenNotFound()
    {
        $nodes = new Nodes();
        $this->expectException(\OutOfRangeException::class);
        $this->expectExceptionMessage('The index 0 does not exists');
        $nodes->get(0);
    }

    public function testGetWithExistentElements()
    {
        $foo = new Node('foo');
        $bar = new Node('bar');
        $nodes = new Nodes([$foo, $bar]);

        $this->assertSame($foo, $nodes->get(0));
        $this->assertSame($bar, $nodes->get(1));

        // get after remove
        $nodes->remove($foo);
        $this->assertSame($bar, $nodes->get(0));
    }

    public function testGetNodesByName()
    {
        $nodes = new Nodes();
        $first = new Node('children');
        $second = new Node('children');
        $third = new Node('children');
        $nodes->importFromArray([
            $first,
            $second,
            $third,
            new Node('other'),
        ]);

        $this->assertCount(4, $nodes);
        $byName = $nodes->getNodesByName('children');
        $this->assertCount(3, $byName);
        $this->assertTrue($byName->exists($first));
        $this->assertTrue($byName->exists($second));
        $this->assertTrue($byName->exists($third));
    }

    public function testOrderedChildren()
    {
        $nodes = new Nodes([
            new Node('foo'),
            new Node('bar'),
            new Node('baz'),
        ]);
        // test initial order
        $this->assertEquals(
            ['foo', 'bar', 'baz'],
            [$nodes->get(0)->name(), $nodes->get(1)->name(), $nodes->get(2)->name()]
        );

        // sort previous values
        $nodes->setOrder(['baz', '', '0', 'foo', '', 'bar', 'baz']);
        $this->assertEquals(['baz', 'foo', 'bar'], $nodes->getOrder());
        $this->assertEquals(
            ['baz', 'foo', 'bar'],
            [$nodes->get(0)->name(), $nodes->get(1)->name(), $nodes->get(2)->name()]
        );

        // add other baz (inserted at the bottom)
        $nodes->add(new Node('baz', ['id' => 'second']));
        $this->assertEquals(
            ['baz', 'baz', 'foo'],
            [$nodes->get(0)->name(), $nodes->get(1)->name(), $nodes->get(2)->name()]
        );
        $this->assertEquals('second', $nodes->get(1)['id']);

        // add other not listed
        $notListed = new Node('yyy');
        $nodes->add($notListed);
        $this->assertSame($notListed, $nodes->get(4));
    }
}
