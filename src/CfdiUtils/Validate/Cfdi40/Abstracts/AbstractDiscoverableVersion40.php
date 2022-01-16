<?php

namespace CfdiUtils\Validate\Cfdi40\Abstracts;

use CfdiUtils\Validate\Contracts\DiscoverableCreateInterface;
use CfdiUtils\Validate\Contracts\ValidatorInterface;

abstract class AbstractDiscoverableVersion40 extends AbstractVersion40 implements DiscoverableCreateInterface
{
    final public function __construct()
    {
    }

    public static function createDiscovered(): ValidatorInterface
    {
        return new static();
    }
}
