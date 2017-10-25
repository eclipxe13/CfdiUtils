<?php
namespace CfdiUtilsTests\Nodes;

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

    /**
     * @param string $filename
     * @dataProvider providerToNodeFromNode
     */
    public function testExportFromFileAndExportAgain($filename)
    {
        $source = file_get_contents($filename);

        $document = Xml::newDocumentContent($source);

        // create node from element
        $node = XmlNodeUtils::nodeFromXmlElement($document->documentElement);

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
        $this->assertSame('2', $child['id']);
        $this->assertCount(0, $child);
    }
}
