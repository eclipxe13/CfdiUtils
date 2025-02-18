<?php

namespace CfdiUtils\Validate\Traits;

trait XmlStringPropertyTrait
{
    private $xmlString = '';

    public function setXmlString(string $xmlString): void
    {
        $this->xmlString = $xmlString;
    }

    public function getXmlString(): string
    {
        return $this->xmlString;
    }
}
