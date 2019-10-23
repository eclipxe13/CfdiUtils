<?php

namespace CfdiUtils\Validate\Traits;

trait XmlStringPropertyTrait
{
    private $xmlString = '';

    public function setXmlString(string $xmlString)
    {
        $this->xmlString = $xmlString;
    }

    public function getXmlString(): string
    {
        return $this->xmlString;
    }
}
