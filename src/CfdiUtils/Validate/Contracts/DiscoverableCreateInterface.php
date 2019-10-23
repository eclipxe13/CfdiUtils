<?php

namespace CfdiUtils\Validate\Contracts;

interface DiscoverableCreateInterface
{
    public static function createDiscovered(): ValidatorInterface;
}
