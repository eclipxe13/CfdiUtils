<?php
namespace CfdiUtilsTests\OpenSSL;

use CfdiUtils\OpenSSL\PemExtractor;
use CfdiUtilsTests\TestCase;

class PemExtractorTest extends TestCase
{
    public function testEmptyExtractor()
    {
        $extractor = new PemExtractor('');
        $this->assertSame('', $extractor->getContents());
        $container = $extractor->pemContainer();
        $this->assertFalse($container->hasCertificate());
        $this->assertFalse($container->hasPublicKey());
        $this->assertFalse($container->hasPrivateKey());
        $this->assertFalse($container->hasAny());
    }

    public function testExtractCertificateWithPublicKey()
    {
        $pemcerpub = $this->utilAsset('certs/CSD01_AAA010101AAA.cer.pem');
        $contents = strval(file_get_contents($pemcerpub));

        $extractor = new PemExtractor($contents);
        $this->assertSame($contents, $extractor->getContents());

        $container = $extractor->pemContainer();
        $this->assertTrue($container->hasCertificate());
        $this->assertNotEmpty($container->publicKey());
        $this->assertTrue($container->hasPublicKey());
        $this->assertNotEmpty($container->certificate());
        $this->assertFalse($container->hasPrivateKey());
        $this->assertEmpty($container->privateKey());
        $this->assertTrue($container->hasAny());
    }

    public function testExtractPrivateKey()
    {
        $pemkey = $this->utilAsset('certs/CSD01_AAA010101AAA.key.pem');
        $contents = strval(file_get_contents($pemkey));

        $extractor = new PemExtractor($contents);
        $container = $extractor->pemContainer();

        $this->assertFalse($container->hasCertificate());
        $this->assertFalse($container->hasPublicKey());
        $this->assertTrue($container->hasPrivateKey());
        $this->assertTrue($container->hasAny());
    }

    public function testUsingBinaryFileExtractNothing()
    {
        $pemkey = $this->utilAsset('certs/CSD01_AAA010101AAA.key');
        $contents = strval(file_get_contents($pemkey));

        $extractor = new PemExtractor($contents);
        $container = $extractor->pemContainer();

        $this->assertFalse($container->hasCertificate());
        $this->assertFalse($container->hasPublicKey());
        $this->assertFalse($container->hasPrivateKey());
    }
}
