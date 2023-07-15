<?php

namespace CfdiUtilsTests\QuickReader;

use CfdiUtils\QuickReader\QuickReader;
use CfdiUtils\QuickReader\QuickReaderImporter;
use DOMDocument;
use PHPUnit\Framework\TestCase;

final class QuickReaderImporterTest extends TestCase
{
    public function testImporterImportEmptyNode()
    {
        $document = new DOMDocument();
        $document->loadXML('<root/>');

        $importer = new QuickReaderImporter();
        $root = $importer->importDocument($document);

        $this->assertInstanceOf(QuickReader::class, $root);
        $this->assertCount(0, $root());
    }

    public function testImporterImportEmptyNodeWithNamespaces()
    {
        $document = new DOMDocument();
        $document->loadXML('<my:root xmlns:my="http://my.net/my" />');

        $importer = new QuickReaderImporter();
        $root = $importer->importDocument($document);

        $this->assertInstanceOf(QuickReader::class, $root);
        $this->assertCount(0, $root());
    }

    public function testImporterImportWithAttributes()
    {
        $document = new DOMDocument();
        $document->loadXML('<root id="123" score="MEX 1 - 0 GER"/>');

        $importer = new QuickReaderImporter();
        $root = $importer->importDocument($document);

        $this->assertInstanceOf(QuickReader::class, $root);
        $this->assertCount(0, $root());
        $this->assertSame('123', $root['id']);
        $this->assertSame('MEX 1 - 0 GER', $root['score']);
    }

    public function testImporterImportWithChildren()
    {
        $document = new DOMDocument();
        $document->loadXML('<root><foo /><bar /></root>');

        $importer = new QuickReaderImporter();
        $root = $importer->importDocument($document);

        $this->assertInstanceOf(QuickReader::class, $root);
        $this->assertCount(2, $root());
        $this->assertSame('foo', (string) $root->foo);
        $this->assertSame('bar', (string) $root->bar);
        $this->assertCount(2, $root());
    }

    public function testImporterImportWithGrandChildren()
    {
        $document = new DOMDocument();
        $document->loadXML('<root><foo><l1><l2 id="xee"></l2></l1></foo><bar /></root>');

        $importer = new QuickReaderImporter();
        $root = $importer->importDocument($document);

        $this->assertInstanceOf(QuickReader::class, $root);
        $this->assertSame('xee', (string) $root->foo->l1->l2['id']);
    }

    public function testImportXmlWithDifferentNodes()
    {
        $document = new DOMDocument();
        $document->loadXML('
            <root>
                <!-- comment -->
                <foo />
            </root>
        ');

        $importer = new QuickReaderImporter();
        $root = $importer->importDocument($document);
        $this->assertTrue(isset($root->foo));
        $this->assertCount(1, $root());
        $this->assertCount(0, ($root->foo)());
    }

    public function testImportChildrenWithSameName()
    {
        $document = new DOMDocument();
        $document->loadXML('
            <root>
                <foo id="1"/>
                <Foo id="2"/>
                <FOO id="3"/>
                <bar />
                <bar />
                <baz />
            </root>
        ');

        $importer = new QuickReaderImporter();
        $root = $importer->importDocument($document);

        $this->assertCount(6, $root());
        $this->assertCount(3, $root('foo'));
        $this->assertCount(2, $root('bar'));
        $this->assertCount(1, $root('baz'));
        $this->assertCount(0, $root('xee'));
    }

    public function testImportWithNamespacesAreExcluded()
    {
        $document = new DOMDocument();
        $document->loadXML('
            <my:root xmlns:my="http://example.com/my" xmlns:other="http://example.com/other">
                <my:foo id="1"/>
                <my:Foo id="2"/>
                <my:FOO id="3"/>
                <other:bar />
                <other:bar />
                <other:baz />
            </my:root>
        ');

        $importer = new QuickReaderImporter();
        $root = $importer->importDocument($document);

        $this->assertCount(6, $root());
        $this->assertCount(3, $root('foo'));
        $this->assertCount(2, $root('bar'));
        $this->assertCount(1, $root('baz'));
        $this->assertCount(0, $root('xee'));
    }
}
