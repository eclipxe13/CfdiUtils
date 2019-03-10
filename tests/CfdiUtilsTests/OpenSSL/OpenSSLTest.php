<?php
namespace CfdiUtilsTests\OpenSSL;

use CfdiUtils\OpenSSL\OpenSSL;
use CfdiUtilsTests\TestCase;

class OpenSSLTest extends TestCase
{
    // test to work with converted DER to PEM private key are in OpenSSLPrivateKeyTest

    public function testCreateInstance()
    {
        $openssl = new OpenSSL('foobar');
        $this->assertSame('foobar', $openssl->getOpenSSLPath());
    }

    public function testCertificateIsPem()
    {
        $openssl = new OpenSSL();

        $certificate = strval(file_get_contents($this->utilAsset('certs/CSD01_AAA010101AAA.cer')));
        $this->assertFalse($openssl->certificateIsPEM($certificate));

        $certificate = strval(file_get_contents($this->utilAsset('certs/CSD01_AAA010101AAA.cer.pem')));
        $this->assertTrue($openssl->certificateIsPEM($certificate));
    }

    public function testPrivateKeyIsPem()
    {
        $openssl = new OpenSSL();

        $key = strval(file_get_contents($this->utilAsset('certs/CSD01_AAA010101AAA.key')));
        $this->assertFalse($openssl->privateKeyIsPEM($key));

        $key = strval(file_get_contents($this->utilAsset('certs/CSD01_AAA010101AAA.key.pem')));
        $this->assertTrue($openssl->privateKeyIsPEM($key));

        $rsa = strval(file_get_contents($this->utilAsset('certs/CSD01_AAA010101AAA_password.key.pem')));
        $this->assertTrue($openssl->privateKeyIsPEM($rsa));
    }

    public function testConvertCertificateToPEM()
    {
        $openssl = new OpenSSL();

        $certificate = strval(file_get_contents($this->utilAsset('certs/CSD01_AAA010101AAA.cer')));
        $expectedPem = strval(file_get_contents($this->utilAsset('certs/CSD01_AAA010101AAA.cer.pem')));

        $converted = $openssl->convertCertificateToPEM($certificate);

        $this->assertStringContainsString($converted, $expectedPem);
    }
}
