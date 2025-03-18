<?php

namespace CfdiUtilsTests\ConsultaCfdiSat;

use CfdiUtils\ConsultaCfdiSat\Config;
use CfdiUtilsTests\TestCase;

final class ConfigTest extends TestCase
{
    public function testConstructorDefaultValues(): void
    {
        $config = new Config();
        $this->assertSame(10, $config->getTimeout());
        $this->assertSame(true, $config->shouldVerifyPeer());
        $this->assertSame($config::DEFAULT_SERVICE_URL, $config->getServiceUrl());
    }

    public function testConstructorWithOtherData(): void
    {
        $config = new Config(99, false, 'foo');
        $this->assertSame(99, $config->getTimeout());
        $this->assertSame(false, $config->shouldVerifyPeer());
        $this->assertSame('foo', $config->getServiceUrl());
    }
}
