<?php
namespace CfdiUtilsTests\OpenSSL;

use CfdiUtils\OpenSSL\OpenSSL;
use CfdiUtils\OpenSSL\OpenSSLPropertyTrait;
use CfdiUtilsTests\TestCase;

class OpenSSLPropertyTest extends TestCase
{
    public function testCorrectImplementer()
    {
        $correctImplementer = new class() {
            use OpenSSLPropertyTrait;

            public function __construct(OpenSSL $openSSL = null)
            {
                $this->setOpenSSL($openSSL ?: new OpenSSL());
            }
        };

        $this->assertInstanceOf(OpenSSL::class, $correctImplementer->getOpenSSL());
    }

    public function testNotInstantiatedImplementer()
    {
        $correctImplementer = new class() {
            use OpenSSLPropertyTrait;
        };

        $this->expectException(\TypeError::class);
        $correctImplementer->getOpenSSL();
    }
}
