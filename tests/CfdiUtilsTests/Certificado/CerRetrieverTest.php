<?php
namespace CfdiUtilsTests\Certificado;

use CfdiUtils\Certificado\Certificado;
use CfdiUtils\Certificado\SatCertificateNumber;
use CfdiUtilsTests\TestCase;
use XmlResourceRetriever\Downloader\PhpDownloader;

class CerRetrieverTest extends TestCase
{
    public function testRetrieveNonExistent()
    {
        // this certificate does not exists in the internet repository, it will fail to download
        $certificateId = '20001000000300022779';
        $cerNumber = new SatCertificateNumber($certificateId);
        $retriever = $this->newResolver()->newCerRetriever();
        $remoteUrl = $cerNumber->remoteUrl();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage($remoteUrl);
        $retriever->retrieve($remoteUrl);
    }

    public function testRetrieveValidCertificate()
    {
        // NOTE: This certificate is valid until 2021-05-22 12:42:41
        // after this date this test may fail
        $certificateId = '00001000000406258094';
        $cerNumber = new SatCertificateNumber($certificateId);
        // disable ssl verification connecting to https://rdc.sat.gob.mx/ since web server has config errors
        $downloader = new PhpDownloader(stream_context_create(['ssl' => ['verify_peer' => false]]));
        $retriever = $this->newResolver($downloader)->newCerRetriever();
        $remoteUrl = $cerNumber->remoteUrl();
        $localPath = $retriever->buildPath($remoteUrl);

        if (file_exists($localPath)) {
            unlink($localPath);
        }
        $this->assertFileNotExists($localPath);

        $retriever->retrieve($remoteUrl);
        $this->assertFileExists($localPath);

        $certificate = new Certificado($localPath);
        $this->assertSame($certificateId, $certificate->getSerial());
    }
}
