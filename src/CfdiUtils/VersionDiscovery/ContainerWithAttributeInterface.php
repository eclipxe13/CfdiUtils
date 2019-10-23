<?php

namespace CfdiUtils\VersionDiscovery;

interface ContainerWithAttributeInterface
{
    public function getAttributeValue(string $attribute): string;
}
