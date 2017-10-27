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

        // with spaces
        $attributes->set('  foo  ', 'foo with spaces');
        $this->assertCount(2, $attributes);
        $this->assertSame('foo with spaces', $attributes->get('foo'));
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
            'foo' => 'bar',
        ]);
        $this->assertTrue($attributes->exists('foo'));
        $attributes->importArray([
            'foo' => null,
        ]);
        $this->assertFalse($attributes->exists('foo'));
    }
}
