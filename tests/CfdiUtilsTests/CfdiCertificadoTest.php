<?php
namespace CfdiUtilsTests;

use CfdiUtils\CfdiCertificado;

class CfdiCertificadoTest extends TestCase
{
    public function testExtractWithWrongVersion()
    {
        $cfdi = CfdiCertificado::newFromString(
            '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" Version="1.9.80"' . '/>'
        );

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unsupported or unknown version');
        $cfdi->extract();
    }

    public function testExtractWithEmptyCertificate()
    {
        $cfdi = CfdiCertificado::newFromString(
            '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" Version="3.3"' . '/>'
        );

        $this->assertEmpty($cfdi->extract());
    }

    public function testExtractWithMalformedBase64()
    {
        $cfdi = CfdiCertificado::newFromString(
            '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" Version="3.3" Certificado="ñ"' . '/>'
        );

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('The certificado attribute is not a valid base64 encoded string');
        $cfdi->extract();
    }

    public function testExtract()
    {
        $expectedExtract = 'foo';

        $cfdi = CfdiCertificado::newFromString(
            '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" Version="3.3" Certificado="Zm9v"' . '/>'
        );

        $this->assertSame($expectedExtract, $cfdi->extract());
    }

    public function testSaveWithEmptyFilename()
    {
        $cfdi = CfdiCertificado::newFromString(
            '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" Version="3.3" Certificado="Zm9v"' . '/>'
        );

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('The filename to store the certificate is empty');
        $cfdi->save('');
    }

    public function testSaveWithEmptyCertificado()
    {
        $cfdi = CfdiCertificado::newFromString(
            '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" Version="3.3"' . '/>'
        );

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('The certificado attribute is empty');
        $cfdi->save(__DIR__);
    }

    public function testSaveWithUnwritableFilename()
    {
        $cfdi = CfdiCertificado::newFromString(
            '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" Version="3.3" Certificado="Zm9v"' . '/>'
        );

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unable to write the certificate contents');
        $cfdi->save(__DIR__);
    }

    public function testSave()
    {
        $cfdi = CfdiCertificado::newFromString(
            '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" Version="3.3" Certificado="Zm9v"' . '/>'
        );

        $tempfile = tempnam('', '');
        $cfdi->save($tempfile);
        $this->assertFileExists($tempfile);
        $this->assertStringEqualsFile($tempfile, 'foo');
        unlink($tempfile);
    }

    public function testObtain()
    {
        $cfdiSample = $this->utilAsset('cfdi32-real.xml');
        $cfdi = CfdiCertificado::newFromString(
            file_get_contents($cfdiSample)
        );

        $certificate = $cfdi->obtain();
        $this->assertFileNotExists($certificate->getFilename());
        $this->assertEquals('CTO021007DZ8', $certificate->getRfc());
    }
}
