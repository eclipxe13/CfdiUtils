<?php

namespace CfdiUtilsTests\Nodes;

use CfdiUtils\Nodes\Node;
use CfdiUtils\Nodes\NodesSorter;
use PHPUnit\Framework\TestCase;

class NodesSorterTest extends TestCase
{
    public function testConstructWithNames()
    {
        $values = ['foo', 'bar', 'baz'];
        $sorter = new NodesSorter($values);
        $this->assertSame($values, $sorter->getOrder());
    }

    public function testConstructWithoutNames()
    {
        $sorter = new NodesSorter();
        $this->assertSame([], $sorter->getOrder());
    }

    public function testParseNames()
    {
        $sorter = new NodesSorter();
        // all invalid values
        $this->assertSame([], $sorter->parseNames([null, new \stdClass(), 0, false, '']));
        // all valid values
        $this->assertSame(['foo', 'bar'], $sorter->parseNames(['foo', 'bar']));
        // suplicated values
        $this->assertSame(['foo', 'bar', 'baz'], $sorter->parseNames(['foo', 'bar', 'bar', 'foo', 'baz']));
        // mixed values
        $this->assertSame(['foo', 'bar'], $sorter->parseNames(['', 'foo', '', 'bar', '', 'foo']));
    }

    public function testSetGetOrder()
    {
        $sorter = new NodesSorter(['foo', 'bar']);
        $this->assertSame(['foo', 'bar'], $sorter->getOrder());

        // it change
        $this->assertTrue($sorter->setOrder(['bar', 'foo']));
        $this->assertSame(['bar', 'foo'], $sorter->getOrder());

        // it did not change
        $this->assertFalse($sorter->setOrder(['bar', 'foo']));
        $this->assertSame(['bar', 'foo'], $sorter->getOrder());
    }

    public function testOrder()
    {
        $foo1 = new Node('foo');
        $foo2 = new Node('foo');
        $bar = new Node('bar');
        $baz = new Node('baz');
        $yyy = new Node('yyy');

        $order = ['baz', 'bar', 'foo'];
        $unsorted = [$yyy, $foo1, $foo2, $bar, $baz];
        $expected = [$baz, $bar, $foo1, $foo2, $yyy];

        $sorter = new NodesSorter($order);
        $sorted = $sorter->sort($unsorted);
        $this->assertSame($expected, $sorted);
    }

    public function testOrderPreservePosition()
    {
        $list = [];
        for ($i = 0; $i < 1000; $i++) {
            $list[] = new Node('foo');
        }
        $sorter = new NodesSorter(['foo']);
        $this->assertSame($list, $sorter->sort($list));
    }
}
