<?php
namespace CfdiUtils;

use \DOMDocument;
use \DOMElement;
use CfdiUtils\Nodes\NodeInterface;

/**
 * This class provides static methods to retrieve the version attribute from a cfdi.
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
        return Cfdi::newFromString($contents)->getVersion();
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
