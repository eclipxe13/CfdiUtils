<?php
namespace CfdiUtils\VersionDiscovery;

use \DOMDocument;
use \DOMElement;
use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Utils\Xml;

/**
 * @internal Use for compatibility
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
        return static::createDiscoverer()->discover(new DomElementContainer($element));
    }

    /**
     * @param DOMDocument $document
     * @return string
     * @deprecated :3.0.0 Replaced with object instanced methods
     */
    public static function fromDOMDocument(DOMDocument $document): string
    {
        return static::fromDOMElement($document->documentElement);
    }

    /**
     * @param NodeInterface $node
     * @return string
     * @deprecated :3.0.0 Replaced with object instanced methods
     */
    public static function fromNode(NodeInterface $node): string
    {
        return static::createDiscoverer()->discover(new NodeContainer($node));
    }

    /**
     * @param string $contents
     * @return string
     * @deprecated :3.0.0 Replaced with object instanced methods
     */
    public static function fromXmlString(string $contents): string
    {
        return static::fromDOMDocument(Xml::newDocumentContent($contents));
    }
}
