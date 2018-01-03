<?php
namespace CfdiUtils\TimbreFiscalDigital;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Utils\Xml;
use DOMDocument;
use DOMElement;

class TfdVersion
{
    public static function fromDOMElement(DOMElement $element): string
    {
        return self::evaluate($element->getAttribute('version'), $element->getAttribute('Version'));
    }

    public static function fromDOMDocument(DOMDocument $document): string
    {
        return static::fromDOMElement($document->documentElement);
    }

    public static function fromNode(NodeInterface $node): string
    {
        return self::evaluate($node['version'], $node['Version']);
    }

    public static function fromXmlString(string $contents): string
    {
        $document = Xml::newDocumentContent($contents);
        return static::fromDOMDocument($document);
    }

    private static function evaluate(string $v10, string $v11): string
    {
        if ('1.1' === $v11) {
            return '1.1';
        }
        if ('1.0' === $v10) {
            return '1.0';
        }
        return '';
    }
}
