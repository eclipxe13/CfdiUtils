<?php

namespace CfdiUtils\Nodes;

use DOMElement;
use DOMNode;
use DOMText;

class XmlNodeImporter
{
    /**
     * Local record for registered namespaces to avoid set the namespace declaration in every child
     * @var string[]
     */
    private array $registeredNamespaces = [];

    public function import(DOMElement $element): NodeInterface
    {
        $node = new Node($element->tagName);

        $node->setValue($this->extractValue($element));

        if ('' !== $element->prefix) {
            $this->registerNamespace($node, 'xmlns:' . $element->prefix, $element->namespaceURI);
            $this->registerNamespace($node, 'xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        }

        /** @var DOMNode $attribute */
        foreach ($element->attributes as $attribute) {
            $node[$attribute->nodeName] = $attribute->nodeValue;
        }
        // element is like <element namespace="uri"/>
        if ($element->hasAttributeNS('http://www.w3.org/2000/xmlns/', '')) {
            $node['xmlns'] = $element->getAttributeNS('http://www.w3.org/2000/xmlns/', '');
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

    private function registerNamespace(Node $node, string $prefix, string $uri): void
    {
        if (isset($this->registeredNamespaces[$prefix])) {
            return;
        }
        $this->registeredNamespaces[$prefix] = $uri;
        $node[$prefix] = $uri;
    }

    private function extractValue(DOMElement $element): string
    {
        $values = [];
        foreach ($element->childNodes as $childElement) {
            if (! $childElement instanceof DOMText) {
                continue;
            }
            $values[] = $childElement->wholeText;
        }

        return implode('', $values);
    }
}
