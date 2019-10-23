<?php

namespace CfdiUtils\VersionDiscovery;

use DOMElement;

class DomElementContainer implements ContainerWithAttributeInterface
{
    /** @var DOMElement */
    private $element;

    public function __construct(DOMElement $element)
    {
        $this->element = $element;
    }

    public function getAttributeValue(string $attribute): string
    {
        return $this->element->getAttribute($attribute);
    }
}
