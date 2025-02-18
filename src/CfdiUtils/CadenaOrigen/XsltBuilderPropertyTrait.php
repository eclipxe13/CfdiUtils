<?php

namespace CfdiUtils\CadenaOrigen;

trait XsltBuilderPropertyTrait
{
    private ?XsltBuilderInterface $xsltBuilder = null;

    public function hasXsltBuilder(): bool
    {
        return ($this->xsltBuilder instanceof XsltBuilderInterface);
    }

    public function getXsltBuilder(): XsltBuilderInterface
    {
        if (! $this->xsltBuilder instanceof XsltBuilderInterface) {
            throw new \LogicException('There is no current xsltBuilder');
        }
        return $this->xsltBuilder;
    }

    public function setXsltBuilder(?XsltBuilderInterface $xsltBuilder = null): void
    {
        $this->xsltBuilder = $xsltBuilder;
    }
}
