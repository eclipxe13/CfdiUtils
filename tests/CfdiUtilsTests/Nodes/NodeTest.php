<?php
namespace CfdiUtilsTests\Nodes;

use CfdiUtils\Nodes\Node;
use CfdiUtilsTests\TestCase;

class NodeTest extends TestCase
{
    public function testConstructWithoutArguments()
    {
        $node = new Node('name');
        $this->assertSame('name', $node->name());
        $this->assertCount(0, $node->attributes());
        $this->assertCount(0, $node->children());
    }

    public function testConstructWithArguments()
    {
        $dummyNode = new Node('dummy');
        $attributes = ['foo' => 'bar'];
        $children = [$dummyNode];
        $node = new Node('name', $attributes, $children);
        $this->assertSame('bar', $node->attributes()->get('foo'));
        $this->assertSame($dummyNode, $node->children()->firstNodeWithName('dummy'));
    }

    public function testConstructWithEmptyName()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Node name cannot be empty');
        new Node('');
    }

    public function testConstructWithUntrimmedEmptyName()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Node name cannot be empty');
        new Node("\n  \t  \n");
    }

    public function testConstructWithUntrimmedName()
    {
        $node = new Node(' x ');
        $this->assertSame('x', $node->name());
    }

    public function testSearchAttribute()
    {
        $node = new Node('root', ['level' => '1'], [
            new Node('child', ['level' => '2'], [
                new Node('grandchild', ['level' => '3.1']),
                new Node('grandchild', ['level' => '3.2']),
            ]),
        ]);

        $this->assertSame('1', $node->searchAttribute('level'));
        $this->assertSame('2', $node->searchAttribute('child', 'level'));
        $this->assertSame('3.1', $node->searchAttribute('child', 'grandchild', 'level'));

        $this->assertSame('', $node->searchAttribute('not-found-child', 'child', 'grandchild', 'level'));
        $this->assertSame('', $node->searchAttribute('not-found-attribute'));
    }

    public function testSearchNode()
    {
        $grandChildOne = new Node('grandchild', ['level' => '3.1']);
        $grandChildTwo = new Node('grandchild', ['level' => '3.2']);
        $child = new Node('child', ['level' => '2'], [$grandChildOne, $grandChildTwo]);
        $root = new Node('root', ['level' => '1'], [$child]);

        $this->assertSame($root, $root->searchNode());
        $this->assertSame($child, $root->searchNode('child'));
        $this->assertSame($grandChildOne, $root->searchNode('child', 'grandchild'));

        $this->assertNull($root->searchNode('child', 'grandchild', 'not-found'));
        $this->assertNull($root->searchNode('not-found', 'child', 'grandchild'));
        $this->assertNull($root->searchNode('not-found'));
    }

    public function testSearchNodes()
    {
        $grandChildOne = new Node('grandchild', ['level' => '3.1']);
        $grandChildTwo = new Node('grandchild', ['level' => '3.2']);
        $child = new Node('child', ['level' => '2'], [$grandChildOne, $grandChildTwo]);
        $root = new Node('root', ['level' => '1'], [$child]);

        $nodesChild = $root->searchNodes('child');
        $this->assertCount(1, $nodesChild);
        $this->assertSame($child, $nodesChild->first());

        $nodesGrandChild = $root->searchNodes('child', 'grandchild');
        $this->assertCount(2, $nodesGrandChild);
        $this->assertSame($grandChildOne, $nodesGrandChild->get(0));
        $this->assertSame($grandChildTwo, $nodesGrandChild->get(1));

        $this->assertCount(0, $root->searchNodes('child', 'grandchild', 'not-found'));
        $this->assertCount(0, $root->searchNodes('not-found', 'child', 'grandchild'));
        $this->assertCount(0, $root->searchNodes('not-found'));
    }

    public function testArrayAccessToAttributes()
    {
        $node = new Node('x');
        $node['id'] = 'form';

        $this->assertTrue(isset($node['id']));
        $this->assertSame('form', $node['id']);

        $node['id'] = 'the-form';
        $this->assertSame('the-form', $node['id']);

        unset($node['id']);
        $this->assertFalse(isset($node['id']));
        $this->assertSame('', $node['id']);
    }
}
