<?php

namespace CfdiUtilsTests\XmlResolver;

use CfdiUtils\Certificado\SatCertificateNumber;
use CfdiUtils\XmlResolver\XmlResolver;
use CfdiUtilsTests\TestCase;
use Eclipxe\XmlResourceRetriever\Downloader\DownloaderInterface;

final class XmlResolverTest extends TestCase
{
    public function testConstructor(): void
    {
        $resolver = new XmlResolver();
        $this->assertEquals($resolver->defaultLocalPath(), $resolver->getLocalPath());
        $this->assertTrue($resolver->hasLocalPath());
        $this->assertInstanceOf(DownloaderInterface::class, $resolver->getDownloader());
    }

    public function testSetLocalPath(): void
    {
        $default = XmlResolver::defaultLocalPath();
        $customPath = '/temporary/resources/';

        // constructed
        $resolver = new XmlResolver();
        $this->assertEquals($default, $resolver->getLocalPath());
        $this->assertTrue($resolver->hasLocalPath());

        // change to empty '' (disable)
        $resolver->setLocalPath('');
        $this->assertEquals('', $resolver->getLocalPath());
        $this->assertFalse($resolver->hasLocalPath());

        // change to custom value
        $resolver->setLocalPath($customPath);
        $this->assertEquals($customPath, $resolver->getLocalPath());
        $this->assertTrue($resolver->hasLocalPath());

        // change to default value
        $resolver->setLocalPath(null);
        $this->assertEquals($default, $resolver->getLocalPath());
        $this->assertTrue($resolver->hasLocalPath());
    }

    public function testRetrieveWithoutLocalPath(): void
    {
        $resolver = new XmlResolver('');
        $this->assertFalse($resolver->hasLocalPath());

        $resource = 'http://example.com/schemas/example.xslt';

        $this->assertEquals($resource, $resolver->resolve($resource));
    }

    /*
     * This test will download xslt for cfdi 3.3 from
     * http://www.sat.gob.mx/sitio_internet/cfd/3/cadenaoriginal_3_3/cadenaoriginal_3_3.xslt
     * and all its relatives and put it in the default path of XmlResolver (project root + build + resources)
     */
    public function testRetrieveWithDefaultLocalPath(): void
    {
        $resolver = new XmlResolver();
        $this->assertTrue($resolver->hasLocalPath());

        $endpoint = 'http://www.sat.gob.mx/sitio_internet/cfd/3/cadenaoriginal_3_3/cadenaoriginal_3_3.xslt';
        $localResource = $resolver->resolve($endpoint);

        $this->assertNotEmpty($localResource);
        $this->assertFileExists($localResource);
    }

    public function testResolveThrowsExceptionWhenUnknownResourceIsSet(): void
    {
        $resolver = new XmlResolver();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unable to handle the resource');

        $resolver->resolve('http://example.org/example.xml');
    }

    public function providerObtainTypeFromUrl(): array
    {
        return [
            'xsd' => ['http://example.com/resource.xsd', XmlResolver::TYPE_XSD],
            'xlst' => ['http://example.com/resource.xslt', XmlResolver::TYPE_XSLT],
            'cer' => ['http://example.com/resource.cer', XmlResolver::TYPE_CER],
            'unknown' => ['http://example.com/resource.xml', ''],
            'empty' => ['', ''],
            'end with xml but no extension' => ['http://example.com/xml', ''],
        ];
    }

    /**
     * @dataProvider providerObtainTypeFromUrl
     */
    public function testObtainTypeFromUrl(string $url, string $expectedType): void
    {
        $resolver = new XmlResolver();
        $this->assertEquals($expectedType, $resolver->obtainTypeFromUrl($url));
    }

    public function testResolveCerFileWithExistentFile(): void
    {
        // preinstall certificate to avoid the download
        $localPath = $this->installCertificate($this->utilAsset('certs/20001000000300022779.cer'));

        $certificateId = '20001000000300022779';
        $cerNumber = new SatCertificateNumber($certificateId);
        $resolver = new XmlResolver();
        $remoteUrl = $cerNumber->remoteUrl();

        // this downloader will throw an exception if downloadTo is called
        $nullDownloader = new class () implements DownloaderInterface {
            public function downloadTo(string $source, string $destination): void
            {
                throw new \RuntimeException("$source will not be downloaded to $destination");
            }
        };

        // set the downloader into the resolver
        $resolver->setDownloader($nullDownloader);

        // call to resolve, it must not throw an exception
        $resolvedPath = $resolver->resolve($remoteUrl, $resolver::TYPE_CER);

        $this->assertSame($localPath, $resolvedPath);
    }
}
