<?php

namespace CfdiUtils\Validate\Contracts;

interface RequireXmlStringInterface
{
    /**
     * @param string $xmlString
     * @return void
     */
    public function setXmlString(string $xmlString);

    public function getXmlString(): string;
}
