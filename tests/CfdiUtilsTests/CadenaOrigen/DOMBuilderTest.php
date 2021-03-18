<?php

namespace CfdiUtilsTests\CadenaOrigen;

use CfdiUtils\CadenaOrigen\DOMBuilder;
use CfdiUtils\CadenaOrigen\XsltBuilderInterface;

final class DOMBuilderTest extends GenericBuilderTestCase
{
    protected function createBuilder(): XsltBuilderInterface
    {
        return new DOMBuilder();
    }
}
