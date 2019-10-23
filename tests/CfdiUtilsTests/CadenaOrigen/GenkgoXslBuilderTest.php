<?php

namespace CfdiUtilsTests\CadenaOrigen;

use CfdiUtils\CadenaOrigen\GenkgoXslBuilder;
use CfdiUtils\CadenaOrigen\XsltBuilderInterface;

class GenkgoXslBuilderTest extends GenericBuilderTestCase
{
    protected function createBuilder(): XsltBuilderInterface
    {
        return new GenkgoXslBuilder();
    }
}
