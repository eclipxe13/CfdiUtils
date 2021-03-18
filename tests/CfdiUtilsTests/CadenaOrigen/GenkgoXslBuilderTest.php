<?php

namespace CfdiUtilsTests\CadenaOrigen;

use CfdiUtils\CadenaOrigen\GenkgoXslBuilder;
use CfdiUtils\CadenaOrigen\XsltBuilderInterface;

final class GenkgoXslBuilderTest extends GenericBuilderTestCase
{
    protected function setUp(): void
    {
        if (! class_exists(\Genkgo\Xsl\XsltProcessor::class)) {
            $this->markTestSkipped('Genkgo/Xsl is not installed');
        }
    }

    protected function createBuilder(): XsltBuilderInterface
    {
        return new GenkgoXslBuilder();
    }
}
