<?php
namespace CfdiUtilsTests\OpenSSL;

use CfdiUtils\OpenSSL\PemExtractor;
use CfdiUtilsTests\TestCase;

class PemExtractorTest extends TestCase
{
    public function testExtractorWithEmptyContent()
    {
        $extractor = new PemExtractor('');
        $this->assertSame('', $extractor->getContents());
        $this->assertSame('', $extractor->extractCertificate());
        $this->assertSame('', $extractor->extractPublicKey());
        $this->assertSame('', $extractor->extractCertificate());
    }

    public function testExtractorWithFakeContent()
    {
        // section contents must be base64 valid strings
        $content = implode(PHP_EOL, [
            '-----BEGIN CERTIFICATE-----',
            'FOO+CERTIFICATE',
            '-----END CERTIFICATE-----',
            '-----BEGIN PUBLIC KEY-----',
            'FOO+PUBLIC+KEY',
            '-----END PUBLIC KEY-----',
            '-----BEGIN PRIVATE KEY-----',
            'FOO+PRIVATE+KEY',
            '-----END PRIVATE KEY-----',
        ]);
        $extractor = new PemExtractor($content);
        $this->assertSame($content, $extractor->getContents());
        $this->assertContains('FOO+CERTIFICATE', $extractor->extractCertificate());
        $this->assertContains('FOO+PUBLIC+KEY', $extractor->extractPublicKey());
        $this->assertContains('FOO+PRIVATE+KEY', $extractor->extractPrivateKey());
    }

    public function testExtractCertificateWithPublicKey()
    {
        $pemcerpub = $this->utilAsset('certs/CSD01_AAA010101AAA.cer.pem');
        $contents = strval(file_get_contents($pemcerpub));

        $extractor = new PemExtractor($contents);
        $this->assertSame($contents, $extractor->getContents());

        $this->assertContains('PUBLIC KEY', $extractor->extractPublicKey());
        $this->assertContains('CERTIFICATE', $extractor->extractCertificate());
    }

    public function testExtractPrivateKey()
    {
        $pemkey = $this->utilAsset('certs/CSD01_AAA010101AAA.key.pem');
        $contents = strval(file_get_contents($pemkey));

        $extractor = new PemExtractor($contents);
        $this->assertContains('PRIVATE KEY', $extractor->extractPrivateKey());
    }

    public function testUsingBinaryFileExtractNothing()
    {
        $pemkey = $this->utilAsset('certs/CSD01_AAA010101AAA.key');
        $contents = strval(file_get_contents($pemkey));

        $extractor = new PemExtractor($contents);

        $this->assertSame('', $extractor->extractCertificate());
        $this->assertSame('', $extractor->extractPublicKey());
        $this->assertSame('', $extractor->extractPrivateKey());
    }
}
