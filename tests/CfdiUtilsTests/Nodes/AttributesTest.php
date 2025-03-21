<?php

namespace CfdiUtilsTests\Nodes;

use CfdiUtils\Nodes\Attributes;
use CfdiUtilsTests\TestCase;

final class AttributesTest extends TestCase
{
    public function testConstructWithoutArguments(): void
    {
        $attributes = new Attributes();
        $this->assertCount(0, $attributes);
    }

    public function testConstructWithMembers(): void
    {
        $data = [
            'id' => 'sample',
            'foo' => 'bar',
        ];
        $attributes = new Attributes($data);
        $this->assertCount(2, $attributes);
        foreach ($data as $key => $value) {
            $this->assertTrue($attributes->exists($key));
            $this->assertSame($value, $attributes->get($key));
        }
    }

    public function providerSetMethodWithInvalidName(): array
    {
        return [
            'empty' => [''],
            'white space' => ['   '],
        ];
    }

    /**
     * @dataProvider providerSetMethodWithInvalidName
     */
    public function testSetMethodWithInvalidName(string $name): void
    {
        $attributes = new Attributes();
        $this->expectException(\UnexpectedValueException::class);
        $attributes->set($name, '');
    }

    public function testSetMethod(): void
    {
        $attributes = new Attributes();
        // first
        $attributes->set('foo', 'bar');
        $this->assertCount(1, $attributes);
        $this->assertSame('bar', $attributes->get('foo'));
        // second
        $attributes->set('lorem', 'ipsum');
        $this->assertCount(2, $attributes);
        $this->assertSame('ipsum', $attributes->get('lorem'));
        // override
        $attributes->set('foo', 'BAR');
        $this->assertCount(2, $attributes);
        $this->assertSame('BAR', $attributes->get('foo'));
    }

    public function providerSetWithInvalidNames(): array
    {
        return [
            'empty' => [''],
            'white space' => [' '],
            'digit' => ['0'],
            'digit hyphen text' => ['0-foo'],
            'hyphen' => ['-'],
            'hyphen text' => ['-x'],
            'inner space' => ['foo bar'],
        ];
    }

    /**
     * @dataProvider providerSetWithInvalidNames
     */
    public function testSetWithInvalidNames(string $name): void
    {
        $attributes = new Attributes();

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('invalid xml name');

        $attributes->set($name, '');
    }

    public function testGetMethodOnNonExistent(): void
    {
        $attributes = new Attributes();
        $this->assertSame('', $attributes->get('foo'));
    }

    public function testRemove(): void
    {
        $attributes = new Attributes();
        $attributes->set('foo', 'bar');

        $attributes->remove('bar');
        $this->assertCount(1, $attributes);

        $attributes->remove('foo');
        $this->assertCount(0, $attributes);
    }

    public function testArrayAccess(): void
    {
        $attributes = new Attributes();
        $attributes['id'] = 'sample';
        $attributes['foo'] = 'foo foo foo';
        $attributes['foo'] = 'bar'; // override
        $attributes['empty'] = '';
        $this->assertCount(3, $attributes);
        // existent
        $this->assertTrue(isset($attributes['empty']));
        $this->assertTrue(isset($attributes['id']));
        $this->assertSame('sample', $attributes['id']);
        $this->assertSame('bar', $attributes['foo']);
        // non existent
        $this->assertFalse(isset($attributes['non-existent']));
        $this->assertSame('', $attributes['non-existent']);
        // remove and check
        unset($attributes['foo']);
        $this->assertSame('', $attributes['foo']);
    }

    public function testIterator(): void
    {
        $data = [
            'foo' => 'bar',
            'lorem' => 'ipsum',
        ];
        $created = [];
        $attributes = new Attributes($data);
        foreach ($attributes as $key => $value) {
            $created[$key] = $value;
        }
        $this->assertEquals($data, $created);
    }

    public function testSetToNullPerformRemove(): void
    {
        $attributes = new Attributes([
            'foo' => 'bar',
        ]);
        $this->assertTrue($attributes->exists('foo'));
        $attributes['foo'] = null;
        $this->assertFalse($attributes->exists('foo'));
    }

    public function testImportWithNullPerformRemove(): void
    {
        $attributes = new Attributes([
            'set' => '1',
            'importArray' => '1',
            'offsetSet' => '1',
            'constructor' => null,
        ]);
        $this->assertFalse($attributes->exists('constructor'));
        $this->assertCount(3, $attributes);

        $attributes->set('set', null);
        $this->assertFalse($attributes->exists('set'));
        $this->assertCount(2, $attributes);

        $attributes->importArray(['importArray' => null]);
        $this->assertFalse($attributes->exists('importArray'));
        $this->assertCount(1, $attributes);

        $attributes['offsetSet'] = null;
        $this->assertFalse($attributes->exists('offsetSet'));
        $this->assertCount(0, $attributes);
    }

    public function testImportWithInvalidValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot convert value of attribute foo to string');
        new Attributes([
            'foo' => [],
        ]);
    }

    public function testSetWithObjectToString(): void
    {
        $expectedValue = 'foo';
        $toStringObject = new class ('foo') {
            public function __construct(private string $value)
            {
            }

            public function __toString(): string
            {
                return $this->value;
            }
        };
        $attributes = new Attributes(['constructor' => $toStringObject]);
        $attributes['offsetSet'] = $toStringObject;
        $attributes->set('set', $toStringObject);
        $attributes->importArray(['importArray' => $toStringObject]);
        $this->assertEquals($expectedValue, $attributes->get('constructor'));
        $this->assertEquals($expectedValue, $attributes->get('offsetSet'));
        $this->assertEquals($expectedValue, $attributes->get('set'));
        $this->assertEquals($expectedValue, $attributes->get('importArray'));
    }

    public function testExportArray(): void
    {
        $attributes = new Attributes();
        $attributes->set('foo', 'bar');

        $this->assertEquals(['foo' => 'bar'], $attributes->exportArray());
    }
}
