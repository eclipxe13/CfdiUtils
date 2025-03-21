<?php

namespace CfdiUtils\CadenaOrigen;

interface XsltBuilderInterface
{
    /**
     * Transform XML content to a string using XSLT
     *
     * @throws \UnexpectedValueException if the xml content is empty
     * @throws \UnexpectedValueException if the xslt location is empty
     * @throws \RuntimeException on procedural errors
     */
    public function build(string $xmlContent, string $xsltLocation): string;
}
