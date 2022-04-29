<?php

namespace CfdiUtils\Nodes;

use CfdiUtils\Utils\Xml;
use DOMDocument;
use DOMElement;

class XmlNodeExporter
{
    public function export(NodeInterface $node): DOMElement
    {
        $document = Xml::newDocument();
        $rootElement = $this->exportRecursive($document, $node);
        $document->appendChild($rootElement);
        return $rootElement;
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

        if ($node instanceof NodeHasValueInterface && '' !== $node->value()) {
            $element->appendChild($document->createTextNode($node->value()));
        }

        return $element;
    }
}
