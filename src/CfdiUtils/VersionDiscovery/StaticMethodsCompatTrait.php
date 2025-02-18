<?php

namespace CfdiUtils\VersionDiscovery;

use CfdiUtils\Nodes\NodeInterface;
use DOMDocument;
use DOMElement;

/**
 * Use this trait for compatibility only, all methods are deprecated.
 * @internal
 * @codeCoverageIgnore
 */
trait StaticMethodsCompatTrait
{
    abstract protected static function createDiscoverer(): VersionDiscoverer;

    /**
     * @deprecated :3.0.0 Replaced with object instanced methods
     */
    public static function fromDOMElement(DOMElement $element): string
    {
        return static::createDiscoverer()->getFromDOMElement($element);
    }

    /**
     * @deprecated :3.0.0 Replaced with object instanced methods
     */
    public static function fromDOMDocument(DOMDocument $document): string
    {
        return static::createDiscoverer()->getFromDOMDocument($document);
    }

    /**
     * @deprecated :3.0.0 Replaced with object instanced methods
     */
    public static function fromNode(NodeInterface $node): string
    {
        return static::createDiscoverer()->getFromNode($node);
    }

    /**
     * @deprecated :3.0.0 Replaced with object instanced methods
     */
    public static function fromXmlString(string $contents): string
    {
        return static::createDiscoverer()->getFromXmlString($contents);
    }
}
