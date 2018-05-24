<?php
namespace CfdiUtils;

use \DOMDocument;
use \DOMElement;
use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Utils\Xml;

/**
 * This class provides static methods to retrieve the version attribute from a cfdi.
 * It will not check anything but the value of the correct attribute
 * It will not care if the cfdi is following an schema or root element's name
 *
 * Possible values are always 3.2, 3.3 or empty string
 */
class CfdiVersion
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

    private static function evaluate(string $v32, string $v33): string
    {
        if ('3.3' === $v33) {
            return '3.3';
        }
        if ('3.2' === $v32) {
            return '3.2';
        }
        return '';
    }
}
