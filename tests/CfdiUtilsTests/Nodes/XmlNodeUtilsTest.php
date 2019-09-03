<?php
namespace CfdiUtilsTests\Nodes;

use CfdiUtils\Nodes\Node;
use CfdiUtils\Nodes\XmlNodeUtils;
use CfdiUtils\Utils\Xml;
use CfdiUtilsTests\TestCase;

class XmlNodeUtilsTest extends TestCase
{
    public function providerToNodeFromNode()
    {
        return [
            'simple-xml' => [$this->utilAsset('nodes/sample.xml')],
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
    public function testExportFromFileAndExportAgain($filename)
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
        if ($inspected === null) {
            $this->fail('The specimen does not have the required test case');
            return;
        }
        $this->assertSame('http://external.com/inner', $inspected['xmlns']);
    }
}
