<?php

namespace CfdiUtilsTests\Validate\FakeObjects;

use CfdiUtils\Validate\Contracts\DiscoverableCreateInterface;
use CfdiUtils\Validate\Contracts\ValidatorInterface;

class ImplementationDiscoverableCreateInterface implements DiscoverableCreateInterface
{
    public static function createDiscovered(): ValidatorInterface
    {
        return new ImplementationValidatorInterface();
    }
}
