<?php

namespace CfdiUtils\Validate\Contracts;

interface RequireXmlStringInterface
{
    public function setXmlString(string $xmlString): void;

    public function getXmlString(): string;
}
