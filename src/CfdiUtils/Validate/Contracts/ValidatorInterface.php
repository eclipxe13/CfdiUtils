<?php

namespace CfdiUtils\Validate\Contracts;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Asserts;

interface ValidatorInterface
{
    /**
     * @param NodeInterface $comprobante
     * @param Asserts $asserts
     * @return void
     */
    public function validate(NodeInterface $comprobante, Asserts $asserts);

    public function canValidateCfdiVersion(string $version): bool;
}
