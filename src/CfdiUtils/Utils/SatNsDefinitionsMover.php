<?php

namespace CfdiUtils\Utils;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Nodes\NodeNsDefinitionsMover;

final class SatNsDefinitionsMover
{
    public function move(NodeInterface $root): void
    {
        $nodeNsDefinitionsMover = new NodeNsDefinitionsMover();
        $nodeNsDefinitionsMover->setNamespaceFilter(
            fn (string $namespaceUri): bool => 'http://www.sat.gob.mx/' === (substr($namespaceUri, 0, 22) ?: '')
        );
        $nodeNsDefinitionsMover->process($root);
    }
}
