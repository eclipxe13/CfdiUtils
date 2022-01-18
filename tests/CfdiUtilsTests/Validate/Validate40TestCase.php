<?php

namespace CfdiUtilsTests\Validate;

use CfdiUtils\Elements\Cfdi40\Comprobante;

abstract class Validate40TestCase extends ValidateBaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->comprobante = new Comprobante();
    }

    /**
     * Use this function to allow code analysis tools to perform correctly
     * @return Comprobante
     */
    protected function getComprobante(): Comprobante
    {
        if ($this->comprobante instanceof Comprobante) {
            return $this->comprobante;
        }
        throw new \RuntimeException('The current comprobante node is not a ' . Comprobante::class);
    }
}
