<?php
namespace CfdiUtils\QuickReader;

use DOMDocument;
use DOMNode;

class QuickReaderImporter
{
    public function importDocument(DOMDocument $document): QuickReader
    {
        return $this->importNode($document->documentElement);
    }

    public function importNode(DOMNode $node): QuickReader
    {
        // localName property has the tagName without namespace prefix
        $name = $node->localName;

        $attributes = [];
        /** @var DOMNode $attribute */
        foreach ($node->attributes as $attribute) {
            $attributes[$attribute->nodeName] = $attribute->nodeValue;
        }

        $children = [];
        /** @var DOMNode $childNode */
        foreach ($node->childNodes as $childNode) {
            if (XML_ELEMENT_NODE === $childNode->nodeType) {
                $children[] = $this->importNode($childNode);
            }
        }

        return new QuickReader($name, $attributes, $children);
    }
}
