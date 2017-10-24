<?php
namespace CfdiUtils\Nodes;

use DOMElement;

class XmlNodeImporter
{
    /**
     * Local record for registered namespaces to avoid set the namespace declaration in every children
     * @var string[]
     */
    private $registeredNamespaces = [];

    public function import($element): Node
    {
        $node = new Node($element->tagName);
        if ('' !== $element->prefix) {
            $this->registerNamespace($node, 'xmlns:' . $element->prefix, $element->namespaceURI);
            $this->registerNamespace($node, 'xmlns:xsi', 'http://www.w3.org/2001/xmlschema-instance');
        }

        /** @var \DOMNode $attribute */
        foreach ($element->attributes as $attribute) {
            $node[$attribute->nodeName] = $attribute->nodeValue;
        }

        /** @var DOMElement $childElement */
        foreach ($element->childNodes as $childElement) {
            if (! $childElement instanceof DOMElement) {
                continue;
            }
            $childNode = $this->import($childElement);
            $node->children()->add($childNode);
        }

        return $node;
    }

    private function registerNamespace(Node $node, string $prefix, string $uri)
    {
        if (isset($this->registeredNamespaces[$prefix])) {
            return;
        }
        $this->registeredNamespaces[$prefix] = $uri;
        $node[$prefix] = $uri;
    }
}
