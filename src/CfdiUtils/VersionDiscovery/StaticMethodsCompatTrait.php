<?php

namespace CfdiUtils\VersionDiscovery;

use \DOMDocument;
use \DOMElement;
use CfdiUtils\Nodes\NodeInterface;

/**
 * Use this trait for compatibility only, all methods are deprecated.
 * @internal
 * @codeCoverageIgnore
 */
trait StaticMethodsCompatTrait
{
    abstract protected static function createDiscoverer(): VersionDiscoverer;

    /**
     * @param DOMElement $element
     * @return string
     * @deprecated :3.0.0 Replaced with object instanced methods
     */
    public static function fromDOMElement(DOMElement $element): string
    {
        return static::createDiscoverer()->getFromDOMElement($element);
    }

    /**
     * @param DOMDocument $document
     * @return string
     * @deprecated :3.0.0 Replaced with object instanced methods
     */
    public static function fromDOMDocument(DOMDocument $document): string
    {
        return static::createDiscoverer()->getFromDOMDocument($document);
    }

    /**
     * @param NodeInterface $node
     * @return string
     * @deprecated :3.0.0 Replaced with object instanced methods
     */
    public static function fromNode(NodeInterface $node): string
    {
        return static::createDiscoverer()->getFromNode($node);
    }

    /**
     * @param string $contents
     * @return string
     * @deprecated :3.0.0 Replaced with object instanced methods
     */
    public static function fromXmlString(string $contents): string
    {
        return static::createDiscoverer()->getFromXmlString($contents);
    }
}
