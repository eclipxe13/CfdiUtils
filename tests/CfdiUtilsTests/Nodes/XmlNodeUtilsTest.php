<?php

namespace CfdiUtilsTests\Nodes;

use CfdiUtils\Nodes\Node;
use CfdiUtils\Nodes\XmlNodeUtils;
use CfdiUtils\Utils\Xml;
use CfdiUtilsTests\TestCase;

final class XmlNodeUtilsTest extends TestCase
{
    public function providerToNodeFromNode(): array
    {
        return [
            'simple-xml' => [$this->utilAsset('nodes/sample.xml')],
            'with-texts-xml' => [$this->utilAsset('nodes/sample-with-texts.xml')],
            'cfdi' => [$this->utilAsset('cfdi33-valid.xml')],
        ];
    }

    public function testNodeToXmlStringXmlHeader()
    {
        $node = new Node('book', [], [
            new Node('chapter', ['toc' => '1']),
            new Node('chapter', ['toc' => '2']),
        ]);

        $xmlString = XmlNodeUtils::nodeToXmlString($node, true);
        $this->assertStringStartsWith('<?xml version="1.0" encoding="UTF-8"?>', $xmlString);

        $xmlString = XmlNodeUtils::nodeToXmlString($node, false);
        $this->assertStringStartsWith('<book>', $xmlString);
    }

    /**
     * @param string $filename
     * @dataProvider providerToNodeFromNode
     */
    public function testExportFromFileAndExportAgain(string $filename)
    {
        $source = strval(file_get_contents($filename));

        $document = Xml::newDocumentContent($source);

        // create node from element
        $node = XmlNodeUtils::nodeFromXmlElement(Xml::documentElement($document));

        // create element from node
        $element = XmlNodeUtils::nodeToXmlElement($node);
        $simpleXml = XmlNodeUtils::nodeToSimpleXmlElement($node);
        $xmlString = XmlNodeUtils::nodeToXmlString($node);

        // compare versus source
        $this->assertXmlStringEqualsXmlString($source, $element->ownerDocument->saveXML($element));
        $this->assertXmlStringEqualsXmlString($source, (string) $simpleXml->asXML());
        $this->assertXmlStringEqualsXmlString($source, $xmlString);
    }

    public function testImportFromSimpleXmlElement()
    {
        $xmlString = '<root id="1"><child id="2"></child></root>';
        $simpleXml = new \SimpleXMLElement($xmlString);
        $node = XmlNodeUtils::nodeFromSimpleXmlElement($simpleXml);
        $this->assertCount(1, $node);
        $this->assertSame('1', $node['id']);
        $child = $node->children()->firstNodeWithName('child');
        if (null === $child) {
            $this->fail('firstNodeWithName did not return a Node');
        } else {
            $this->assertSame('2', $child['id']);
            $this->assertCount(0, $child);
        }
    }

    public function testImportXmlWithNamespaceWithoutPrefix()
    {
        $file = $this->utilAsset('xml-with-namespace-definitions-at-child-level.xml');
        $node = XmlNodeUtils::nodeFromXmlString(file_get_contents($file) ?: '');
        $inspected = $node->searchNode('base:Third', 'innerNS');
        if (null === $inspected) {
            $this->fail('The specimen does not have the required test case');
        }
        $this->assertSame('http://external.com/inner', $inspected['xmlns']);
    }

    public function testXmlWithValueWithSpecialChars()
    {
        $expectedValue = 'ampersand: &';
        $content = '<root>ampersand: &amp;</root>';

        $node = XmlNodeUtils::nodeFromXmlString($content);

        $this->assertSame($expectedValue, $node->value());
        $this->assertSame($content, XmlNodeUtils::nodeToXmlString($node));
    }

    public function testXmlWithValueWithInnerComment()
    {
        $expectedValue = 'ampersand: &';
        $content = '<root>ampersand: <!-- comment -->&amp;</root>';
        $expectedContent = '<root>ampersand: &amp;</root>';

        $node = XmlNodeUtils::nodeFromXmlString($content);

        $this->assertSame($expectedValue, $node->value());
        $this->assertSame($expectedContent, XmlNodeUtils::nodeToXmlString($node));
    }

    public function testXmlWithValueWithInnerWhiteSpace()
    {
        $expectedValue = "\n\nfirst line\n\tsecond line\n\t third line \t\nfourth line\n\n";
        $content = "<root>$expectedValue</root>";

        $node = XmlNodeUtils::nodeFromXmlString($content);

        $this->assertSame($expectedValue, $node->value());
        $this->assertSame($content, XmlNodeUtils::nodeToXmlString($node));
    }

    public function testXmlWithValueWithInnerElement()
    {
        $expectedValue = 'ampersand: &';
        $content = '<root>ampersand: <inner/>&amp;</root>';
        $expectedContent = '<root><inner/>ampersand: &amp;</root>';

        $node = XmlNodeUtils::nodeFromXmlString($content);

        $this->assertSame($expectedValue, $node->value());
        $this->assertSame($expectedContent, XmlNodeUtils::nodeToXmlString($node));
    }
}
