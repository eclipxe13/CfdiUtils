<?php
namespace CfdiUtils\Nodes;

use CfdiUtils\Utils\Xml;
use DOMElement;
use SimpleXMLElement;

class XmlNodeUtils
{
    public static function nodeToXmlElement(NodeInterface $node): DOMElement
    {
        return (new XmlNodeExporter())->export($node);
    }

    public static function nodeToXmlString(NodeInterface $node): string
    {
        $element = static::nodeToXmlElement($node);
        return $element->ownerDocument->saveXML($element);
    }

    public static function nodeToSimpleXmlElement(NodeInterface $node): SimpleXMLElement
    {
        $element = static::nodeToXmlElement($node);
        $simpleXmlElement = simplexml_import_dom($element);
        if (false === $simpleXmlElement) {
            throw new \InvalidArgumentException('Cannot convert to SimpleXmlElement');
        }
        return $simpleXmlElement;
    }

    public static function nodeFromXmlElement(DOMElement $element): NodeInterface
    {
        return (new XmlNodeImporter())->import($element);
    }

    public static function nodeFromXmlString(string $content): NodeInterface
    {
        $document = Xml::newDocumentContent($content);
        return static::nodeFromXmlElement($document->documentElement);
    }

    public static function nodeFromSimpleXmlElement(SimpleXMLElement $element): NodeInterface
    {
        return static::nodeFromXmlString((string) $element->asXML());
    }
}
