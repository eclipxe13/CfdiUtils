<?php
namespace CfdiUtilsTests\OpenSSL;

use CfdiUtils\OpenSSL\OpenSSL;
use CfdiUtils\PemPrivateKey\PemPrivateKey;
use CfdiUtilsTests\TestCase;

abstract class PrivateKeyGenericTestCase extends TestCase
{
    abstract protected function getPrivateKey(): string;

    protected function getOpenSSL(): OpenSSL
    {
        return new OpenSSL();
    }

    protected function getCertificate(): string
    {
        $certificate = strval(file_get_contents($this->utilAsset('certs/CSD01_AAA010101AAA.cer.pem')));
        $certificate = $this->getOpenSSL()->extractCertificate($certificate);
        return $certificate;
    }

    public function testBelongsToCertificate()
    {
        // given the PEM certificate of the converted private key
        $certificate = $this->getCertificate();

        // when open the private key
        $privateKey = new PemPrivateKey($this->getPrivateKey());
        // converted does not have any password
        $this->assertTrue($privateKey->open('12345678a'), 'Cannot open converted private key using blank password');

        // then the private key belong to certificate
        $this->assertTrue($privateKey->belongsTo($certificate));
    }

    public function testCanProtectUsingPassword()
    {
        $newPassPhrase = 'foo$bar#';
        $openssl = $this->getOpenSSL();

        $protected = $openssl->protectPrivateKeyPEM($this->getPrivateKey(), '', $newPassPhrase);

        $privateKey = new PemPrivateKey($protected);
        $this->assertFalse($privateKey->open(''), 'Open protected private key using blank password expected to fail');
        $this->assertTrue($privateKey->open($newPassPhrase), 'Cannot open protected private key using new password');

        $certificate = $this->getCertificate();
        $this->assertTrue($privateKey->belongsTo($certificate), 'Protected private key does not belong to certificate');
    }
}
