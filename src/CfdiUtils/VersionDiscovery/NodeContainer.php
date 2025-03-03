<?php

namespace CfdiUtils\VersionDiscovery;

use CfdiUtils\Nodes\NodeInterface;

class NodeContainer implements ContainerWithAttributeInterface
{
    public function __construct(private NodeInterface $node)
    {
    }

    public function getAttributeValue(string $attribute): string
    {
        return $this->node[$attribute];
    }
}
