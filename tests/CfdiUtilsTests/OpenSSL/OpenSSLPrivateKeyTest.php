<?php
namespace CfdiUtilsTests\OpenSSL;

use CfdiUtils\OpenSSL\OpenSSL;
use CfdiUtils\PemPrivateKey\PemPrivateKey;
use CfdiUtilsTests\TestCase;

class OpenSSLPrivateKeyTest extends TestCase
{
    /** @var string|null */
    private static $converted = null;

    /** @var OpenSSL|null */
    private static $openssl;

    protected function getOpenSSL(): OpenSSL
    {
        if (null === static::$openssl) {
            static::$openssl = new OpenSSL();
        }
        return static::$openssl;
    }

    protected function getConverted(): string
    {
        if (null === static::$converted) {
            $derkey = strval(file_get_contents($this->utilAsset('certs/CSD01_AAA010101AAA.key')));
            static::$converted = $this->getOpenSSL()->convertPrivateKeyDERToPEM($derkey, '12345678a');
        }
        return static::$converted;
    }

    protected function getCertificate(): string
    {
        $certificate = strval(file_get_contents($this->utilAsset('certs/CSD01_AAA010101AAA.cer.pem')));
        $certificate = $this->getOpenSSL()->extractPEMContents($certificate, 'CERTIFICATE');
        $this->assertTrue($this->getOpenSSL()->certificateIsPEM($certificate));
        return $certificate;
    }

    public function testIsPEM()
    {
        $this->assertTrue($this->getOpenSSL()->privateKeyIsPEM($this->getConverted()));
    }

    public function testBelongsToCertificate()
    {
        // given the PEM certificate of the converted private key
        $certificate = $this->getCertificate();

        // when open the private key
        $privateKey = new PemPrivateKey($this->getConverted());
        // converted does not have any password
        $this->assertTrue($privateKey->open(''), 'Cannot open converted private key using blank password');

        // then the private key belong to certificate
        $this->assertTrue($privateKey->belongsTo($certificate));
    }

    public function testCanProtectUsingPassword()
    {
        $newPassPhrase = 'foo$bar#';
        $openssl = $this->getOpenSSL();

        $protected = $openssl->protectPrivateKeyPEM($this->getConverted(), '', $newPassPhrase);
        $this->assertTrue($openssl->privateKeyIsPEM($protected));

        $privateKey = new PemPrivateKey($protected);
        $this->assertFalse($privateKey->open(''), 'Open protected private key using blank password expected to fail');
        $this->assertTrue($privateKey->open($newPassPhrase), 'Cannot open protected private key using new password');

        $certificate = $this->getCertificate();
        $this->assertTrue($privateKey->belongsTo($certificate), 'Protected private key does not belong to certificate');
    }
}
