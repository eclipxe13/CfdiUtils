<?php

namespace CfdiUtilsTests\Certificado;

use CfdiUtils\Certificado\SatCertificateNumber;
use CfdiUtilsTests\TestCase;
use PhpCfdi\Credentials\Certificate;

final class CerRetrieverTest extends TestCase
{
    public function testRetrieveNonExistent()
    {
        // this certificate does not exist in the internet repository, it will fail to download
        $certificateId = '20001000000300022779';
        $cerNumber = SatCertificateNumber::newFromString($certificateId);
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
        $cerNumber = SatCertificateNumber::newFromString($certificateId);
        $retriever = $this->newResolver()->newCerRetriever();
        $retriever->setDownloader(new CertificateDownloaderHelper());
        $remoteUrl = $cerNumber->remoteUrl();
        $localPath = $retriever->buildPath($remoteUrl);

        if (file_exists($localPath)) {
            unlink($localPath);
        }
        $this->assertFileDoesNotExist($localPath);

        $retriever->retrieve($remoteUrl);
        $this->assertFileExists($localPath);

        $certificate = Certificate::openFile($localPath);
        $this->assertSame($certificateId, $certificate->serialNumber()->bytes());
    }
}
