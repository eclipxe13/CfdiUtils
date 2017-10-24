<?php
namespace CfdiUtils\Elements\Common;

use CfdiUtils\Nodes\Node;

abstract class AbstractElement extends Node implements ElementInterface
{
    public function __construct(array $attributes = [], array $children = [])
    {
        parent::__construct($this->getElementName(), $this->getFixedAttributes() + $attributes, $children);
    }

    public function getFixedAttributes(): array
    {
        return [];
    }

    protected function helperGetOrAdd(AbstractElement $element)
    {
        $retrieved = $this->searchNode($element->getElementName());
        if (null !== $retrieved) {
            return $retrieved;
        }
        $this->addChild($element);
        return $element;
    }
}
