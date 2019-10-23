<?php

namespace CfdiUtils\Validate\Cfdi33\Abstracts;

use CfdiUtils\Validate\Contracts\ValidatorInterface;

abstract class AbstractVersion33 implements ValidatorInterface
{
    public function canValidateCfdiVersion(string $version): bool
    {
        return ('3.3' === $version);
    }
}
