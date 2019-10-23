<?php

namespace CfdiUtils\OpenSSL;

trait OpenSSLPropertyTrait
{
    /**
     * Variable to store the instance of OpenSSL
     * You must set this property on the constructor of the class that uses this property,
     * otherwise getOpenSSL will fail
     *
     * To get this property is recommended to use getOpenSSL
     * To set this property is recommended to use setOpenSSL
     *
     * @var OpenSSL
     * @internal
     */
    private $openSSL;

    public function getOpenSSL(): OpenSSL
    {
        return $this->openSSL;
    }

    protected function setOpenSSL(OpenSSL $openSSL)
    {
        $this->openSSL = $openSSL;
    }
}
