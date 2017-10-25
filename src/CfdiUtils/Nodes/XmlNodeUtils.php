<?php
namespace CfdiUtils\Nodes;

use DOMDocument;
use DOMElement;
use SimpleXMLElement;

class XmlNodeUtils
{
    public static function nodeToXmlElement(NodeInterface $node, DOMDocument $document = null): DOMElement
    {
        return (new XmlNodeExporter($document))->export($node);
    }

    public static function nodeToXmlString(NodeInterface $node, DOMDocument $document = null): string
    {
        $element = static::nodeToXmlElement($node, $document);
        return $element->ownerDocument->saveXML($element);
    }

    public static function nodeToSimpleXmlElement(NodeInterface $node, DOMDocument $document = null): SimpleXMLElement
    {
        $element = static::nodeToXmlElement($node, $document);
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
        if ('' == $content) {
            throw new \UnexpectedValueException('Content argument cannot be empty');
        }
        $document = new DOMDocument();
        $document->formatOutput = true;
        $document->preserveWhiteSpace = false;
        // this error silenced call is intentional, no need to alter libxml_use_internal_errors
        if (false === @$document->loadXML($content)) {
            throw new \UnexpectedValueException('Cannot create a DOM Document from content');
        }
        return static::nodeFromXmlElement($document->documentElement);
    }

    public static function nodeFromSimpleXmlElement(SimpleXMLElement $element): NodeInterface
    {
        return static::nodeFromXmlString((string) $element->asXML());
    }
}
