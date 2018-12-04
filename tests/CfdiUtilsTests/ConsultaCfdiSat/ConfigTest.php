<?php
namespace CfdiUtilsTests\ConsultaCfdiSat;

use CfdiUtils\ConsultaCfdiSat\Config;
use CfdiUtilsTests\TestCase;

class ConfigTest extends TestCase
{
    public function testConstructorDefaultValues()
    {
        $config = new Config();
        $this->assertSame(10, $config->getTimeout());
        $this->assertSame(true, $config->shouldVerifyPeer());
        $this->assertSame($config::DEFAULT_WSDL_URL, $config->getServiceUrl());
        $this->assertSame($config::DEFAULT_WSDL_URL, $config->getWsdlLocation());
    }

    public function testConstructorWithOtherData()
    {
        $config = new Config(99, false, 'foo', 'bar');
        $this->assertSame(99, $config->getTimeout());
        $this->assertSame(false, $config->shouldVerifyPeer());
        $this->assertSame('foo', $config->getServiceUrl());
        $this->assertSame('bar', $config->getWsdlLocation());
    }
}
