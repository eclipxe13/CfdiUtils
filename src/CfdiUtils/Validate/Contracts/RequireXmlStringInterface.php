<?php

namespace CfdiUtils\Validate\Contracts;

interface RequireXmlStringInterface
{
    /**
     * @return void
     */
    public function setXmlString(string $xmlString): void;

    public function getXmlString(): string;
}
