<?php

namespace CfdiUtils\Validate\Contracts;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Asserts;

interface ValidatorInterface
{
    /**
     * @return void
     */
    public function validate(NodeInterface $comprobante, Asserts $asserts);

    public function canValidateCfdiVersion(string $version): bool;
}
