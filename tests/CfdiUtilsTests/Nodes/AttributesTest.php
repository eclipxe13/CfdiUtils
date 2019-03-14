<?php
namespace CfdiUtilsTests\Nodes;

use CfdiUtils\Nodes\Attributes;
use CfdiUtilsTests\TestCase;

class AttributesTest extends TestCase
{
    public function testConstructWithoutArguments()
    {
        $attributes = new Attributes();
        $this->assertCount(0, $attributes);
    }

    public function testConstructWithMembers()
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

    public function providerSetMethodWithInvalidName()
    {
        return [
            'empty' => [''],
            'white space' => ['   '],
        ];
    }

    /**
     * @param string $name
     * @dataProvider providerSetMethodWithInvalidName
     */
    public function testSetMethodWithInvalidName(string $name)
    {
        $attributes = new Attributes();
        $this->expectException(\UnexpectedValueException::class);
        $attributes->set($name, '');
    }

    public function testSetMethod()
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

    public function providerSetWithInvalidNames()
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
     * @param string $name
     * @dataProvider providerSetWithInvalidNames
     */
    public function testSetWithInvalidNames($name)
    {
        $attributes = new Attributes();

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('invalid xml name');

        $attributes->set($name, '');
    }

    public function testGetMethodOnNonExistent()
    {
        $attributes = new Attributes();
        $this->assertSame('', $attributes->get('foo'));
    }

    public function testRemove()
    {
        $attributes = new Attributes();
        $attributes->set('foo', 'bar');

        $attributes->remove('bar');
        $this->assertCount(1, $attributes);

        $attributes->remove('foo');
        $this->assertCount(0, $attributes);
    }

    public function testArrayAccess()
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

    public function testIterator()
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

    public function testSetToNullPerformRemove()
    {
        $attributes = new Attributes([
            'foo' => 'bar',
        ]);
        $this->assertTrue($attributes->exists('foo'));
        $attributes['foo'] = null;
        $this->assertFalse($attributes->exists('foo'));
    }

    public function testImportWithNullPerformRemove()
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

    public function testImportWithInvalidValue()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot convert value of attribute foo to string');
        new Attributes([
            'foo' => [],
        ]);
    }

    public function testSetWithObjectToString()
    {
        $expectedValue = 'foo';
        $toStringObject = new class('foo') {
            /** @var string */
            private $value;
            public function __construct(string $value)
            {
                $this->value = $value;
            }
            public function __toString()
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

    public function testExportArray()
    {
        $attributes = new Attributes();
        $attributes->set('foo', 'bar');

        $this->assertEquals(['foo' => 'bar'], $attributes->exportArray());
    }
}
