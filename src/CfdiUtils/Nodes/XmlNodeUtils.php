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

    public static function nodeToXmlString(NodeInterface $node, $withXmlHeader = false): string
    {
        $element = static::nodeToXmlElement($node);
        if ($withXmlHeader) {
            return $element->ownerDocument->saveXML();
        }
        return $element->ownerDocument->saveXML($element);
    }

    public static function nodeToSimpleXmlElement(NodeInterface $node): SimpleXMLElement
    {
        $element = static::nodeToXmlElement($node);
        $simpleXmlElement = simplexml_import_dom($element);
        if (! $simpleXmlElement instanceof SimpleXMLElement) {
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
        return static::nodeFromXmlElement(Xml::documentElement(Xml::newDocumentContent($content)));
    }

    public static function nodeFromSimpleXmlElement(SimpleXMLElement $element): NodeInterface
    {
        return static::nodeFromXmlString((string) $element->asXML());
    }
}
