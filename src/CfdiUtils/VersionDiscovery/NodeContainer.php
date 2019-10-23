<?php

namespace CfdiUtils\VersionDiscovery;

use CfdiUtils\Nodes\NodeInterface;

class NodeContainer implements ContainerWithAttributeInterface
{
    /** @var NodeInterface */
    private $node;

    public function __construct(NodeInterface $node)
    {
        $this->node = $node;
    }

    public function getAttributeValue(string $attribute): string
    {
        return $this->node[$attribute];
    }
}
