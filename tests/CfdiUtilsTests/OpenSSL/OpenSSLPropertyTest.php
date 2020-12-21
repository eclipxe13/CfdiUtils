<?php

namespace CfdiUtilsTests\OpenSSL;

use CfdiUtils\OpenSSL\OpenSSL;
use CfdiUtils\OpenSSL\OpenSSLPropertyTrait;
use CfdiUtilsTests\TestCase;

class OpenSSLPropertyTest extends TestCase
{
    public function testCorrectImplementer()
    {
        $object = new class() {
            use OpenSSLPropertyTrait;

            public function __construct(OpenSSL $openSSL = null)
            {
                $this->setOpenSSL($openSSL ?: new OpenSSL());
            }
        };

        $this->assertInstanceOf(OpenSSL::class, $object->getOpenSSL());
    }

    public function testNotInstantiatedImplementer()
    {
        $object = new class() {
            use OpenSSLPropertyTrait;
        };

        $this->expectException(\TypeError::class);
        /** @noinspection PhpExpressionResultUnusedInspection */
        $object->getOpenSSL();
    }

    public function testWithDefaultSetterVisibility()
    {
        $object = new class() {
            use OpenSSLPropertyTrait;
        };
        $this->assertFalse(is_callable([$object, 'setOpenSSL']), 'setOpenSSL must not be public accesible');
    }

    public function testChangingSetterVisibility()
    {
        $object = new class() {
            use OpenSSLPropertyTrait {
                setOpenSSL as public;
            }
        };
        $this->assertTrue(is_callable([$object, 'setOpenSSL']), 'setOpenSSL was not overrided by trait usage');
    }
}
