<?php

namespace CfdiUtils\CadenaOrigen;

abstract class AbstractXsltBuilder implements XsltBuilderInterface
{
    protected function assertBuildArguments(string $xmlContent, string $xsltLocation): string
    {
        if ('' === $xmlContent) {
            throw new \UnexpectedValueException('The XML content to transform is empty');
        }
        if ('' === $xsltLocation) {
            throw new \UnexpectedValueException('Xslt location was not set');
        }
        return '';
    }
}
