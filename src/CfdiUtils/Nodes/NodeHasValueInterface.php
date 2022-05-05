<?php

namespace CfdiUtils\Nodes;

interface NodeHasValueInterface
{
    public function value(): string;

    public function setValue(string $value): void;
}
