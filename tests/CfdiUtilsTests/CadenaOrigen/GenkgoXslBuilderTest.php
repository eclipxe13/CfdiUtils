<?php

namespace CfdiUtilsTests\CadenaOrigen;

use CfdiUtils\CadenaOrigen\GenkgoXslBuilder;
use CfdiUtils\CadenaOrigen\XsltBuilderInterface;
use Genkgo\Xsl\XsltProcessor;

final class GenkgoXslBuilderTest extends GenericBuilderTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // IGNORE DEPRECATION ERRORS SINCE PHP 8.1
        if (PHP_VERSION_ID >= 80100) {
            set_error_handler(function () {
                return true;
            }, E_DEPRECATED);
        }
        if (! class_exists(XsltProcessor::class)) {
            $this->markTestSkipped('Genkgo/Xsl is not installed');
        }
    }

    protected function tearDown(): void
    {
        if (PHP_VERSION_ID >= 80100) {
            restore_error_handler();
        }
        parent::tearDown();
    }

    protected function createBuilder(): XsltBuilderInterface
    {
        return new GenkgoXslBuilder();
    }
}
