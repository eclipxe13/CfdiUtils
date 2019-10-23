<?php

namespace CfdiUtils\VersionDiscovery;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Utils\Xml;
use DOMDocument;
use DOMElement;

abstract class VersionDiscoverer
{
    /**
     * This method should be implemented and return array of key/value elements
     * where the key is the version number
     * and the value is the attribute to query
     *
     * @return array
     */
    abstract public function rules(): array;

    public function discover(ContainerWithAttributeInterface $container)
    {
        foreach ($this->rules() as $versionNumber => $attribute) {
            $currentValue = $container->getAttributeValue($attribute);
            if ($versionNumber === $currentValue) {
                return $versionNumber;
            }
        }

        return '';
    }

    public function getFromDOMElement(DOMElement $element): string
    {
        return $this->discover(new DomElementContainer($element));
    }

    public function getFromDOMDocument(DOMDocument $document): string
    {
        return $this->getFromDOMElement(Xml::documentElement($document));
    }

    public function getFromNode(NodeInterface $node): string
    {
        return $this->discover(new NodeContainer($node));
    }

    public function getFromXmlString(string $contents): string
    {
        return $this->getFromDOMDocument(Xml::newDocumentContent($contents));
    }
}
