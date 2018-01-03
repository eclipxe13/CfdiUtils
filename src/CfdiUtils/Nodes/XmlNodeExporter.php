<?php
namespace CfdiUtils\Nodes;

use CfdiUtils\Utils\Xml;
use DOMDocument;
use DOMElement;

class XmlNodeExporter
{
    public function export(NodeInterface $node): DOMElement
    {
        return $this->exportRecursive(Xml::newDocument(), $node);
    }

    private function exportRecursive(DOMDocument $document, NodeInterface $node): DOMElement
    {
        $element = $document->createElement($node->name());

        foreach ($node->attributes() as $name => $value) {
            $element->setAttribute($name, $value);
        }

        foreach ($node->children() as $child) {
            $childElement = $this->exportRecursive($document, $child);
            $element->appendChild($childElement);
        }

        return $element;
    }
}
