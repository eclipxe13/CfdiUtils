<?php

namespace CfdiUtilsTests\QuickReader;

use CfdiUtils\QuickReader\QuickReader;
use PHPUnit\Framework\TestCase;

final class QuickReaderTest extends TestCase
{
    public function testMinimalInstance(): void
    {
        $tree = new QuickReader('foo');
        $this->assertSame('foo', (string) $tree);
        $this->assertCount(0, $tree());
    }

    public function testConstructorWithAttributes(): void
    {
        $attributes = [
            'one' => '1',
            'two' => '2',
        ];

        $foo = new QuickReader('foo', $attributes, []);

        $this->assertSame('1', $foo['one']);
        $this->assertSame('2', $foo['two']);
    }

    public function testConstructorWithChildren(): void
    {
        $children = [
            $bar = new QuickReader('bar'),
            $baz = new QuickReader('baz'),
        ];
        $tree = new QuickReader('foo', [], $children);

        $this->assertSame($children, $tree());
        $this->assertSame($bar, $tree->bar);
        $this->assertSame($baz, $tree->baz);
    }

    public function testGetNotExistentAttribute(): void
    {
        $foo = new QuickReader('foo');

        $this->assertFalse(isset($foo['bar']));
        $this->assertSame('', $foo['bar']);
        $this->assertFalse(isset($foo['bar']));
    }

    public function testAccessNonExistentPropertyReturnsANewChildWithPropertyName(): void
    {
        $foo = new QuickReader('foo');

        $this->assertFalse(isset($foo->bar));

        $this->assertInstanceOf(QuickReader::class, $foo->bar);
        $this->assertFalse(isset($foo->bar));
        $this->assertCount(0, $foo(), 'Calling a non existent property DOES NOT append a new child');
    }

    public function testAccessInvokeReturnsAnArray(): void
    {
        $foo = new QuickReader('foo');
        $this->assertTrue(is_array($foo())); /** @phpstan-ignore-line */
        $xee = $foo->bar->xee;
        $this->assertTrue(is_array($xee('zee')));
        $this->assertTrue(is_array($xee->__invoke('zee')));
        $this->assertTrue(is_array(($foo->bar->xee)('zee')));
    }

    public function testAccessInvokeReturnsAnArrayOfChildrenWithTheArgumentName(): void
    {
        $manyBaz = [
            $firstBaz = new QuickReader('baz'),
            new QuickReader('baz'),
            new QuickReader('baz'),
        ];

        $manyChildren = array_merge($manyBaz, [
            new QuickReader('xee'),
        ]);

        $foo = new QuickReader('foo', [], $manyChildren);
        $this->assertCount(4, $foo(), 'Assert that contains 4 children');

        $this->assertSame($firstBaz, $foo->baz, 'Assert that the first child is the same as the property access');

        $obtainedBaz = $foo('baz');
        $this->assertSame($manyBaz, $obtainedBaz, 'Assert that all elements where retrieved');
        $this->assertCount(3, $obtainedBaz, 'Assert that contains only 3 baz children');
    }

    public function testPropertyGetWithDifferentCaseStillWorks(): void
    {
        $bar = new QuickReader('bar');
        $foo = new QuickReader('foo', [], [$bar]);

        $this->assertSame($bar, $foo->bar);
        $this->assertTrue(isset($foo->bar));

        $this->assertSame($bar, $foo->Bar);
        $this->assertTrue(isset($foo->Bar));

        $this->assertSame($bar, $foo->BAR);
        $this->assertTrue(isset($foo->BAR));

        $this->assertSame($bar, $foo->bAR);
        $this->assertTrue(isset($foo->bAR));
    }

    public function testAttributeGetWithDifferentCaseStillWorks(): void
    {
        $foo = new QuickReader('foo', ['bar' => 'México']);

        $this->assertSame('México', $foo['bar']);
        $this->assertTrue(isset($foo['bar']));

        $this->assertSame('México', $foo['Bar']);
        $this->assertTrue(isset($foo['Bar']));

        $this->assertSame('México', $foo['BAR']);
        $this->assertTrue(isset($foo['BAR']));
    }

    public function testInvokeWithDifferentChildNamesCase(): void
    {
        $fooA = new QuickReader('foo');
        $fooB = new QuickReader('Foo');
        $fooC = new QuickReader('FOO');

        $root = new QuickReader('root', [], [$fooA, $fooB, $fooC]);

        $this->assertCount(3, $root('fOO'));
    }

    public function testConstructThrowExceptionOnEmptyName(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Property name cannot be empty');
        new QuickReader('');
    }

    public function testConstructThrowExceptionOnInvalidAttributeName(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('There is an attibute with empty or non string name');
        new QuickReader('foo', ['x' => 'y', '' => 'bar']);
    }

    public function testConstructThrowExceptionOnInvalidAttributeValue(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("The attribute 'bar' has a non string value");
        $fakeString = new \stdClass();
        new QuickReader('foo', ['x' => 'y', 'bar' => $fakeString]); /** @phpstan-ignore-line */
    }

    public function testConstructThrowExceptionOnInvalidChildren(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('The child 1 is not an instance');
        /** @var QuickReader $fakeQuickReader */
        $fakeQuickReader = new \stdClass();
        new QuickReader('foo', [], [new QuickReader('1'), $fakeQuickReader]);
    }

    public function testCannotSetNewProperties(): void
    {
        $quickReader = new QuickReader('foo');
        $this->expectException(\LogicException::class);
        $quickReader->foo = new QuickReader('xee');
    }

    public function testReadFalsyAttributes(): void
    {
        $quickReader = new QuickReader('foo', [
            'zero' => '0',
            'empty' => '',
            'space' => ' ',
            'control' => 'x',
        ]);

        $this->assertTrue(isset($quickReader['control']));
        $this->assertSame('x', $quickReader['control']);

        $this->assertTrue(isset($quickReader['zero']));
        $this->assertSame('0', $quickReader['zero']);

        $this->assertTrue(isset($quickReader['empty']));
        $this->assertSame('', $quickReader['empty']);

        $this->assertTrue(isset($quickReader['space']));
        $this->assertSame(' ', $quickReader['space']);

        $this->assertFalse(isset($quickReader['non-existent']));
        $this->assertSame('', $quickReader['non-existent']);
    }
}
