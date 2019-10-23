<?php

namespace CfdiUtils\CadenaOrigen;

interface XsltBuilderPropertyInterface
{
    public function hasXsltBuilder(): bool;

    public function getXsltBuilder(): XsltBuilderInterface;

    public function setXsltBuilder(XsltBuilderInterface $xsltBuilder = null);
}
