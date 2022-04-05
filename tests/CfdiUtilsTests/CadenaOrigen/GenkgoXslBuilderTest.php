<?php

namespace CfdiUtilsTests\CadenaOrigen;

use CfdiUtils\CadenaOrigen\GenkgoXslBuilder;
use CfdiUtils\CadenaOrigen\XsltBuilderInterface;
use Genkgo\Xsl\XsltProcessor;

final class GenkgoXslBuilderTest extends GenericBuilderTestCase
{
    protected function setUp(): void
    {
        if (! class_exists(XsltProcessor::class)) {
            $this->markTestSkipped('Genkgo/Xsl is not installed');
        }
    }

    protected function createBuilder(): XsltBuilderInterface
    {
        return new GenkgoXslBuilder();
    }
}
