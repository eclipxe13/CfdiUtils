<?php
namespace CfdiUtilsTests\OpenSSL;

use CfdiUtils\OpenSSL\OpenSSL;
use CfdiUtilsTests\TestCase;

class OpenSSLTest extends TestCase
{
    public function testCreateInstance()
    {
        $openssl = new OpenSSL('foobar');
        $this->assertSame('foobar', $openssl->getOpenSSLPath());
    }

    public function testConvertCertificateToPEM()
    {
        $openssl = new OpenSSL();
        $certificateDer = strval(file_get_contents($this->utilAsset('certs/CSD01_AAA010101AAA.cer')));
        $certificatePem = strval(file_get_contents($this->utilAsset('certs/CSD01_AAA010101AAA.cer.pem')));
        $expected = $openssl->extractCertificate($certificatePem);

        $converted = $openssl->convertCertificateToPEM($certificateDer);

        $this->assertSame($expected, $converted);
    }
}
