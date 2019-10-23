<?php

namespace CfdiUtils\QuickReader;

use CfdiUtils\Utils\Xml;
use DOMDocument;
use DOMNode;

class QuickReaderImporter
{
    public function importDocument(DOMDocument $document): QuickReader
    {
        return $this->importNode(Xml::documentElement($document));
    }

    public function importNode(DOMNode $node): QuickReader
    {
        return $this->createQuickReader(
            $this->extractNameFromNode($node),
            $this->extractAttributes($node),
            $this->extractChildren($node)
        );
    }

    protected function extractNameFromNode(DOMNode $node): string
    {
        // localName property has the tagName without namespace prefix
        return $node->localName;
    }

    protected function extractAttributes(DOMNode $node): array
    {
        $attributes = [];
        /** @var DOMNode $attribute */
        foreach ($node->attributes as $attribute) {
            $attributes[$attribute->nodeName] = $attribute->nodeValue;
        }

        return $attributes;
    }

    /**
     * @param DOMNode $node
     * @return QuickReader[]
     */
    protected function extractChildren(DOMNode $node): array
    {
        $children = [];
        /** @var DOMNode $childNode */
        foreach ($node->childNodes as $childNode) {
            if (XML_ELEMENT_NODE === $childNode->nodeType) {
                $children[] = $this->importNode($childNode);
            }
        }

        return $children;
    }

    /**
     * @param string $name
     * @param array $attributes
     * @param QuickReader[] $children
     * @return QuickReader
     */
    protected function createQuickReader(string $name, array $attributes, array $children): QuickReader
    {
        return new QuickReader($name, $attributes, $children);
    }
}
