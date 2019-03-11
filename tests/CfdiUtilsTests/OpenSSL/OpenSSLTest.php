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

    public function testConvertPrivateKeyFileDERToPEMWithInvalidPathToOpenssl()
    {
        $openssl = new OpenSSL('/invalid/openssl');
        $derPrimaryKeyFile = $this->utilAsset('certs/CSD01_AAA010101AAA.key');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('OpenSSL execution error');
        $openssl->convertPrivateKeyFileDERToPEM($derPrimaryKeyFile, '12345678a');
    }

    public function testConvertPrivateKeyFileDERToPEMWithInvalidInputKey()
    {
        $openssl = new OpenSSL();
        $derPrimaryKeyFile = $this->utilAsset('certs/CSD01_AAA010101AAA.key.notfound');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('OpenSSL execution error');
        $openssl->convertPrivateKeyFileDERToPEM($derPrimaryKeyFile, '12345678a');
    }
}
