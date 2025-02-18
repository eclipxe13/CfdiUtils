<?php

namespace CfdiUtilsTests\OpenSSL;

use CfdiUtils\OpenSSL\OpenSSL;
use CfdiUtils\OpenSSL\OpenSSLCallerException;
use CfdiUtils\PemPrivateKey\PemPrivateKey;
use CfdiUtilsTests\TestCase;

final class OpenSSLTest extends TestCase
{
    public function testCreateInstanceWithoutAnyArguments(): void
    {
        $openssl = new OpenSSL();
        $this->assertSame('openssl', $openssl->getOpenSSLCommand());
    }

    public function testCreateInstanceWithCaller(): void
    {
        $openssl = new OpenSSL('my-openssl-executable');
        $this->assertSame('my-openssl-executable', $openssl->getOpenSSLCommand());
    }

    public function testReadPemFile(): void
    {
        $pemcer = $this->utilAsset('certs/EKU9003173C9.cer.pem');
        $openssl = new OpenSSL();
        $pem = $openssl->readPemFile($pemcer);
        $this->assertTrue($pem->hasPublicKey());
        $this->assertTrue($pem->hasCertificate());
        $this->assertFalse($pem->hasPrivateKey());
    }

    public function testCertificateConvertContentsDerToPem(): void
    {
        $openssl = new OpenSSL();
        $cerFile = $this->utilAsset('certs/EKU9003173C9.cer');
        $cerContents = strval(file_get_contents($cerFile));
        $pemFile = $this->utilAsset('certs/EKU9003173C9.cer.pem');
        $expected = $openssl->readPemFile($pemFile)->certificate();

        $converted = $openssl->derCerConvertPhp($cerContents);

        $this->assertSame($expected, $converted);
    }

    public function testCertificateConvertFilesDerToPem(): void
    {
        $openssl = new OpenSSL();
        $keyFile = $this->utilAsset('certs/EKU9003173C9.cer');
        $cerContents = strval(file_get_contents($keyFile));
        $pemFile = $this->utilAsset('certs/EKU9003173C9.cer.pem');
        $expected = $openssl->readPemFile($pemFile)->certificate();

        $converted = $openssl->derCerConvertInOut($cerContents);

        $this->assertSame($expected, $converted);
    }

    public function testPrivateKeyConvertDerToPem(): void
    {
        $openssl = new OpenSSL();
        $cerFile = $this->utilAsset('certs/EKU9003173C9.key');
        $pemFile = $this->utilAsset('certs/EKU9003173C9.key.pem');
        $expected = $openssl->readPemFile($pemFile)->privateKey();

        $converted = $openssl->derKeyConvertOut($cerFile, '12345678a');

        $this->assertSame($expected, $converted, 'Converted key does not match');
    }

    public function testPrivateKeyConvertDerToPemThrowsExceptionUsingInvalidPassPhrase(): void
    {
        $openssl = new OpenSSL();
        $cerFile = $this->utilAsset('certs/EKU9003173C9.key');
        $this->expectException(OpenSSLCallerException::class);
        $openssl->derKeyConvertOut($cerFile, 'invalid-passphrase');
    }

    public function providerPrivateKeyProtectPem(): array
    {
        return [
            'protect' => ['certs/EKU9003173C9.key.pem', '', 'foo-bar-baz'],
            'change' => ['certs/EKU9003173C9_password.key.pem', '12345678a', 'foo-bar-baz'],
            'unprotect' => ['certs/EKU9003173C9_password.key.pem', '12345678a', ''],
        ];
    }

    /**
     * @dataProvider providerPrivateKeyProtectPem
     */
    public function testPrivateKeyProtectPem(string $pemFile, string $inPassPhrase, string $outPassPhrase): void
    {
        $openssl = new OpenSSL();
        $pemFile = $this->utilAsset($pemFile);
        $pemContents = strval(file_get_contents($pemFile));

        $converted = $openssl->pemKeyProtectInOut($pemContents, $inPassPhrase, $outPassPhrase);

        $privateKey = new PemPrivateKey($converted);
        $this->assertTrue($privateKey->open($outPassPhrase), 'Cannot open the generated private Key');
    }

    /**
     * @testWith [""]
     *           ["foo-bar-baz"]
     */
    public function testPrivateKeyProtectDer(string $outPassPhrase): void
    {
        $derFile = 'certs/EKU9003173C9.key';
        $inPassPhrase = '12345678a';
        $openssl = new OpenSSL();
        $derFile = $this->utilAsset($derFile);

        $converted = $openssl->derKeyProtectOut($derFile, $inPassPhrase, $outPassPhrase);

        $privateKey = new PemPrivateKey($converted);
        $this->assertTrue($privateKey->open($outPassPhrase), 'Cannot open the generated private Key');
    }

    public function testPrivateKeyUnprotectPem(): void
    {
        $pemFile = $this->utilAsset('certs/EKU9003173C9_password.key.pem');
        $pemContents = strval(file_get_contents($pemFile));
        $inPassPhrase = '12345678a';
        $openssl = new OpenSSL();

        $converted = $openssl->pemKeyUnprotectInOut($pemContents, $inPassPhrase);

        $privateKey = new PemPrivateKey($converted);
        $this->assertTrue($privateKey->open(''), 'Cannot open the generated private Key');
    }
}
