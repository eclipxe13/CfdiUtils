<?php
namespace CfdiUtilsTests\Utils;

use CfdiUtils\Utils\SchemaLocations;
use CfdiUtilsTests\TestCase;

class SchemaLocationsTest extends TestCase
{
    public function testConstructorWithEmptyValue()
    {
        $schemaLocations = new SchemaLocations();
        $this->assertSame([], $schemaLocations->pairs());
        $this->assertCount(0, $schemaLocations);
        $this->assertTrue($schemaLocations->isEmpty());
    }

    public function testConstructorWithValidValues()
    {
        $pairs = [
            'http://tempuri.org/my-foo' => 'http://tempuri.org/my-foo.xls',
            'http://tempuri.org/my-bar' => 'http://tempuri.org/my-bar.xls',
        ];
        $schemaLocations = new SchemaLocations($pairs);
        $this->assertSame($pairs, $schemaLocations->pairs());
        $this->assertCount(2, $schemaLocations);
        $this->assertFalse($schemaLocations->isEmpty());
    }

    public function testHasNamespace()
    {
        $pairs = [
            'http://tempuri.org/my-foo' => 'http://tempuri.org/my-foo.xls',
            'http://tempuri.org/my-bar' => 'http://tempuri.org/my-bar.xls',
        ];
        $schemaLocations = new SchemaLocations($pairs);
        $this->assertTrue($schemaLocations->has('http://tempuri.org/my-foo'));
        $this->assertFalse($schemaLocations->has('http://tempuri.org/my-xee'));
    }

    public function testAppendAndRemove()
    {
        $pairs = [
            'http://tempuri.org/my-foo' => 'http://tempuri.org/my-foo.xls',
            'http://tempuri.org/my-bar' => 'http://tempuri.org/my-bar.xls',
        ];
        $schemaLocations = new SchemaLocations($pairs);
        $schemaLocations->append('http://tempuri.org/my-xee', 'http://tempuri.org/my-xee.xls');
        $this->assertTrue($schemaLocations->has('http://tempuri.org/my-xee'));
        $schemaLocations->remove('http://tempuri.org/my-xee');
        $this->assertFalse($schemaLocations->has('http://tempuri.org/my-xee'));
    }

    public function testAsStringWithCompleteValues()
    {
        $pairs = [
            'http://tempuri.org/my-foo' => 'http://tempuri.org/my-foo.xls',
            'http://tempuri.org/my-bar' => 'http://tempuri.org/my-bar.xls',
        ];
        $schemaLocations = new SchemaLocations($pairs);
        $expected = 'http://tempuri.org/my-foo http://tempuri.org/my-foo.xls'
            . ' http://tempuri.org/my-bar http://tempuri.org/my-bar.xls';
        $this->assertSame($expected, $schemaLocations->asString());
    }

    public function testAsStringWithIncompleteValues()
    {
        $pairs = [
            'http://tempuri.org/my-aaa' => '',
            'http://tempuri.org/my-foo' => 'http://tempuri.org/my-foo.xls',
            'http://tempuri.org/my-bbb' => '',
            'http://tempuri.org/my-bar' => 'http://tempuri.org/my-bar.xls',
            'http://tempuri.org/my-ccc' => '',
        ];
        $schemaLocations = new SchemaLocations($pairs);
        $expected = 'http://tempuri.org/my-foo http://tempuri.org/my-foo.xls'
            . ' http://tempuri.org/my-bar http://tempuri.org/my-bar.xls';
        $this->assertSame($expected, $schemaLocations->asString());
    }

    public function testTraverse()
    {
        $pairs = [
            'http://tempuri.org/my-foo' => 'http://tempuri.org/my-foo.xls',
            'http://tempuri.org/my-bar' => 'http://tempuri.org/my-bar.xls',
        ];
        $schemaLocations = new SchemaLocations($pairs);
        $this->assertSame($pairs, iterator_to_array($schemaLocations));
    }

    public function testConstructFromString()
    {
        $input = 'http://tempuri.org/my-foo http://tempuri.org/my-foo.xls'
            . ' http://tempuri.org/my-bar http://tempuri.org/my-bar.xls';
        $schemaLocations = SchemaLocations::fromString($input, false);
        $expected = [
            'http://tempuri.org/my-foo' => 'http://tempuri.org/my-foo.xls',
            'http://tempuri.org/my-bar' => 'http://tempuri.org/my-bar.xls',
        ];
        $this->assertSame($expected, $schemaLocations->pairs());
    }

    public function testConstructFromStringWithOddContentsExcludingLast()
    {
        $input = 'http://tempuri.org/my-foo http://tempuri.org/my-foo.xls'
            . ' http://tempuri.org/my-bar http://tempuri.org/my-bar.xls'
            . ' http://tempuri.org/my-xee';
        $schemaLocations = SchemaLocations::fromString($input, false);
        $expected = [
            'http://tempuri.org/my-foo' => 'http://tempuri.org/my-foo.xls',
            'http://tempuri.org/my-bar' => 'http://tempuri.org/my-bar.xls',
        ];
        $this->assertSame($expected, $schemaLocations->pairs());
    }

    public function testConstructFromStringWithOddContentsIncludingLast()
    {
        $input = 'http://tempuri.org/my-foo http://tempuri.org/my-foo.xls'
            . ' http://tempuri.org/my-bar http://tempuri.org/my-bar.xls'
            . ' http://tempuri.org/my-xee';
        $schemaLocations = SchemaLocations::fromString($input, true);
        $expected = [
            'http://tempuri.org/my-foo' => 'http://tempuri.org/my-foo.xls',
            'http://tempuri.org/my-bar' => 'http://tempuri.org/my-bar.xls',
            'http://tempuri.org/my-xee' => '',
        ];
        $this->assertSame($expected, $schemaLocations->pairs());
    }
}
