<?php

namespace CfdiUtils\Elements\Common;

use CfdiUtils\Nodes\NodeInterface;

interface ElementInterface extends NodeInterface
{
    public function getElementName(): string;

    public function getFixedAttributes(): array;

    public function getChildrenOrder(): array;
}
