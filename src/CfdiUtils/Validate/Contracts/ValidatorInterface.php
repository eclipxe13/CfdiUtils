<?php
namespace CfdiUtils\Validate\Contracts;

use CfdiUtils\Nodes\Node;
use CfdiUtils\Validate\Asserts;

interface ValidatorInterface
{
    /**
     * @param Node $comprobante
     * @param Asserts $asserts
     * @return void
     */
    public function validate(Node $comprobante, Asserts $asserts);

    public function canValidateCfdiVersion(string $version): bool;
}
