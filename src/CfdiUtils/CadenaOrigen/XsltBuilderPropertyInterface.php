<?php
namespace CfdiUtils\CadenaOrigen;

interface XsltBuilderPropertyInterface
{
    public function getXsltBuilder(): XsltBuilderInterface;
    public function setXsltBuilder(XsltBuilderInterface $xsltBuilder);
}
