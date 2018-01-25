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
        $this->assertSame($config::DEFAULT_WSDL_URL, $config->getWsdlUrl());
    }

    public function testConstructorWithOtherData()
    {
        $config = new Config(99, false, 'foo');
        $this->assertSame(99, $config->getTimeout());
        $this->assertSame(false, $config->shouldVerifyPeer());
        $this->assertSame('foo', $config->getWsdlUrl());
    }
}
