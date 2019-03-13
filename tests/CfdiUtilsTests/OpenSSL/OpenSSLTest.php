<?php
namespace CfdiUtilsTests\OpenSSL;

use CfdiUtils\OpenSSL\OpenSSL;
use CfdiUtils\OpenSSL\OpenSSLCallerException;
use CfdiUtils\PemPrivateKey\PemPrivateKey;
use CfdiUtilsTests\TestCase;

class OpenSSLTest extends TestCase
{
    public function testCreateInstanceWithoutAnyArguments()
    {
        $openssl = new OpenSSL();
        $this->assertSame('openssl', $openssl->getOpenSSLCommand());
    }

    public function testCreateInstanceWithCaller()
    {
        $openssl = new OpenSSL('my-openssl-executable');
        $this->assertSame('my-openssl-executable', $openssl->getOpenSSLCommand());
    }

    public function testReadPemFile()
    {
        $pemcer = $this->utilAsset('certs/CSD01_AAA010101AAA.cer.pem');
        $openssl = new OpenSSL();
        $pem = $openssl->readPemFile($pemcer);
        $this->assertTrue($pem->hasPublicKey());
        $this->assertTrue($pem->hasCertificate());
        $this->assertFalse($pem->hasPrivateKey());
    }

    public function testCertificateConvertContentsDerToPem()
    {
        $openssl = new OpenSSL();
        $cerFile = $this->utilAsset('certs/CSD01_AAA010101AAA.cer');
        $cerContents = strval(file_get_contents($cerFile));
        $pemFile = $this->utilAsset('certs/CSD01_AAA010101AAA.cer.pem');
        $expected = $openssl->readPemFile($pemFile)->certificate();

        $converted = $openssl->derCerConvertPhp($cerContents);

        $this->assertSame($expected, $converted);
    }

    public function testCertificateConvertFilesDerToPem()
    {
        $openssl = new OpenSSL();
        $keyFile = $this->utilAsset('certs/CSD01_AAA010101AAA.cer');
        $cerContents = strval(file_get_contents($keyFile));
        $pemFile = $this->utilAsset('certs/CSD01_AAA010101AAA.cer.pem');
        $expected = $openssl->readPemFile($pemFile)->certificate();

        $converted = $openssl->derCerConvertInOut($cerContents);

        $this->assertSame($expected, $converted);
    }

    public function testPrivateKeyConvertDerToPem()
    {
        $openssl = new OpenSSL();
        $cerFile = $this->utilAsset('certs/CSD01_AAA010101AAA.key');
        $pemFile = $this->utilAsset('certs/CSD01_AAA010101AAA.key.pem');
        $expected = $openssl->readPemFile($pemFile)->privateKey();

        $converted = $openssl->derKeyConvertOut($cerFile, '12345678a');

        $this->assertSame($expected, $converted, 'Converted key does not match');
    }

    public function testPrivateKeyConvertDerToPemThrowsExceptionUsingInvalidPassPhrase()
    {
        $openssl = new OpenSSL();
        $cerFile = $this->utilAsset('certs/CSD01_AAA010101AAA.key');
        $this->expectException(OpenSSLCallerException::class);
        $openssl->derKeyConvertOut($cerFile, 'invalid-passphrase');
    }

    public function providerPrivateKeyProtectPem()
    {
        return [
            'protect' => ['certs/CSD01_AAA010101AAA.key.pem', '', 'foo-bar-baz'],
            'change' => ['certs/CSD01_AAA010101AAA_password.key.pem', '12345678a', 'foo-bar-baz'],
            'unprotect' => ['certs/CSD01_AAA010101AAA_password.key.pem', '12345678a', ''],
        ];
    }

    /**
     * @param string $pemFile
     * @param string $inPassPhrase
     * @param string $outPassPhrase
     * @dataProvider providerPrivateKeyProtectPem
     */
    public function testPrivateKeyProtectPem(string $pemFile, string $inPassPhrase, string $outPassPhrase)
    {
        $openssl = new OpenSSL();
        $pemFile = $this->utilAsset($pemFile);
        $pemContents = strval(file_get_contents($pemFile));

        $converted = $openssl->pemKeyProtectInOut($pemContents, $inPassPhrase, $outPassPhrase);

        $privateKey = new PemPrivateKey($converted);
        $this->assertTrue($privateKey->open($outPassPhrase), 'Cannot open the generated private Key');
    }

    /**
     * @param string $outPassPhrase
     * @testWith [""]
     *           ["foo-bar-baz"]
     */
    public function testPrivateKeyProtectDer(string $outPassPhrase)
    {
        $derFile = 'certs/CSD01_AAA010101AAA.key';
        $inPassPhrase = '12345678a';
        $openssl = new OpenSSL();
        $derFile = $this->utilAsset($derFile);

        $converted = $openssl->derKeyProtectOut($derFile, $inPassPhrase, $outPassPhrase);

        $privateKey = new PemPrivateKey($converted);
        $this->assertTrue($privateKey->open($outPassPhrase), 'Cannot open the generated private Key');
    }

    public function testPrivateKeyUnprotectPem()
    {
        $pemFile = $this->utilAsset('certs/CSD01_AAA010101AAA_password.key.pem');
        $pemContents = strval(file_get_contents($pemFile));
        $inPassPhrase = '12345678a';
        $openssl = new OpenSSL();

        $converted = $openssl->pemKeyUnprotectInOut($pemContents, $inPassPhrase);

        $privateKey = new PemPrivateKey($converted);
        $this->assertTrue($privateKey->open(''), 'Cannot open the generated private Key');
    }
}
