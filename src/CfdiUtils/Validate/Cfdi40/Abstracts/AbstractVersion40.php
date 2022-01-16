<?php

namespace CfdiUtils\Validate\Cfdi40\Abstracts;

use CfdiUtils\Validate\Contracts\ValidatorInterface;

abstract class AbstractVersion40 implements ValidatorInterface
{
    public function canValidateCfdiVersion(string $version): bool
    {
        return ('4.0' === $version);
    }
}
