<?php

namespace CfdiUtils\VersionDiscovery;

use DOMElement;

class DomElementContainer implements ContainerWithAttributeInterface
{
    public function __construct(private DOMElement $element)
    {
    }

    public function getAttributeValue(string $attribute): string
    {
        return $this->element->getAttribute($attribute);
    }
}
