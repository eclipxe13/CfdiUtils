<?php

namespace CfdiUtils\XmlResolver;

trait XmlResolverPropertyTrait
{
    private ?XmlResolver $xmlResolver = null;

    public function hasXmlResolver(): bool
    {
        return $this->xmlResolver instanceof XmlResolver;
    }

    public function getXmlResolver(): XmlResolver
    {
        if (! $this->xmlResolver instanceof XmlResolver) {
            throw new \LogicException('There is no current xmlResolver');
        }
        return $this->xmlResolver;
    }

    public function setXmlResolver(?XmlResolver $xmlResolver = null): void
    {
        $this->xmlResolver = $xmlResolver;
    }
}
