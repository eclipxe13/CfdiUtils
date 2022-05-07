<?php

namespace CfdiUtils\Elements\Retenciones20;

use CfdiUtils\Elements\Common\AbstractElement;
use CfdiUtils\Nodes\NodeInterface;

class Addenda extends AbstractElement
{
    public function getElementName(): string
    {
        return 'retenciones:Addenda';
    }

    public function add(NodeInterface $child): self
    {
        $this->children()->add($child);
        return $this;
    }
}
