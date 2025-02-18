<?php

namespace CfdiUtilsTests\Utils;

use CfdiUtils\Utils\SchemaLocations;
use CfdiUtilsTests\TestCase;

final class SchemaLocationsTest extends TestCase
{
    public function testConstructorWithEmptyValue(): void
    {
        $schemaLocations = new SchemaLocations();
        $this->assertSame([], $schemaLocations->pairs());
        $this->assertCount(0, $schemaLocations);
        $this->assertTrue($schemaLocations->isEmpty());
    }

    public function testConstructorWithValidValues(): void
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

    public function testHasNamespace(): void
    {
        $pairs = [
            'http://tempuri.org/my-foo' => 'http://tempuri.org/my-foo.xls',
            'http://tempuri.org/my-bar' => 'http://tempuri.org/my-bar.xls',
        ];
        $schemaLocations = new SchemaLocations($pairs);
        $this->assertTrue($schemaLocations->has('http://tempuri.org/my-foo'));
        $this->assertFalse($schemaLocations->has('http://tempuri.org/my-xee'));
    }

    public function testAppendAndRemove(): void
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

    public function testAsStringWithCompleteValues(): void
    {
        $pairs = [
            'http://tempuri.org/my-foo' => 'http://tempuri.org/my-foo.xls',
            'http://tempuri.org/my-bar' => 'http://tempuri.org/my-bar.xls',
        ];
        $schemaLocations = new SchemaLocations($pairs);
        $expected = 'http://tempuri.org/my-foo http://tempuri.org/my-foo.xls'
            . ' http://tempuri.org/my-bar http://tempuri.org/my-bar.xls';
        $this->assertSame($expected, $schemaLocations->asString());
        $this->assertFalse($schemaLocations->hasAnyNamespaceWithoutLocation());
    }

    public function testAsStringWithIncompleteValues(): void
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
        $this->assertTrue($schemaLocations->hasAnyNamespaceWithoutLocation());
        $this->assertSame([
            'http://tempuri.org/my-aaa',
            'http://tempuri.org/my-bbb',
            'http://tempuri.org/my-ccc',
        ], $schemaLocations->getNamespacesWithoutLocation());
    }

    public function testTraverse(): void
    {
        $pairs = [
            'http://tempuri.org/my-foo' => 'http://tempuri.org/my-foo.xls',
            'http://tempuri.org/my-bar' => 'http://tempuri.org/my-bar.xls',
        ];
        $schemaLocations = new SchemaLocations($pairs);
        $this->assertSame($pairs, iterator_to_array($schemaLocations));
    }

    public function testConstructFromString(): void
    {
        $input = '  http://tempuri.org/my-foo   http://tempuri.org/my-foo.xls  '
            . 'http://tempuri.org/my-bar        http://tempuri.org/my-bar.xls  ';
        $schemaLocations = SchemaLocations::fromString($input, false);
        $expected = [
            'http://tempuri.org/my-foo' => 'http://tempuri.org/my-foo.xls',
            'http://tempuri.org/my-bar' => 'http://tempuri.org/my-bar.xls',
        ];
        $this->assertSame($expected, $schemaLocations->pairs());
    }

    public function testConstructFromStringWithOddContentsExcludingLast(): void
    {
        $input = 'http://tempuri.org/my-foo http://tempuri.org/my-foo.xls'
            . ' http://tempuri.org/my-bar http://tempuri.org/my-bar.xls'
            . ' http://tempuri.org/my-xee';
        $schemaLocations = SchemaLocations::fromString($input, false);
        $this->assertFalse($schemaLocations->hasAnyNamespaceWithoutLocation());
        $expected = [
            'http://tempuri.org/my-foo' => 'http://tempuri.org/my-foo.xls',
            'http://tempuri.org/my-bar' => 'http://tempuri.org/my-bar.xls',
        ];
        $this->assertSame($expected, $schemaLocations->pairs());
    }

    public function testConstructFromStringWithOddContentsIncludingLast(): void
    {
        $input = 'http://tempuri.org/my-foo http://tempuri.org/my-foo.xls'
            . ' http://tempuri.org/my-bar http://tempuri.org/my-bar.xls'
            . ' http://tempuri.org/my-xee';
        $schemaLocations = SchemaLocations::fromString($input, true);
        $this->assertTrue($schemaLocations->hasAnyNamespaceWithoutLocation());
        $expected = [
            'http://tempuri.org/my-foo' => 'http://tempuri.org/my-foo.xls',
            'http://tempuri.org/my-bar' => 'http://tempuri.org/my-bar.xls',
            'http://tempuri.org/my-xee' => '',
        ];
        $this->assertSame($expected, $schemaLocations->pairs());
    }

    public function testConstructFromStringStrictXsd(): void
    {
        // source include spaces to ensure that is working properly
        $source = '  bleh  foo  foo.xsd  bar  baz  zoo  zoo.xsd  baa  xee  xee.xsd  bah  ';
        $schemaLocations = SchemaLocations::fromStingStrictXsd($source);
        $this->assertSame(['bleh', 'bar', 'baz', 'baa', 'bah'], $schemaLocations->getNamespacesWithoutLocation());
        $this->assertSame('foo foo.xsd zoo zoo.xsd xee xee.xsd', $schemaLocations->asString());
    }
}
