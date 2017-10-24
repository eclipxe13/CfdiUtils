<?php
namespace CfdiUtilsTests\Certificado;

use CfdiUtils\Certificado\NodeCertificado;
use CfdiUtils\Cfdi;
use CfdiUtilsTests\TestCase;

class NodeCertificadoTest extends TestCase
{
    private function createNodeCertificado(string $contents)
    {
        return new NodeCertificado(Cfdi::newFromString($contents)->getNode());
    }

    public function testExtractWithWrongVersion()
    {
        $nodeCertificado = $this->createNodeCertificado(
            '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" Version="1.9.80"' . '/>'
        );

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unsupported or unknown version');
        $nodeCertificado->extract();
    }

    public function testExtractWithEmptyCertificate()
    {
        $nodeCertificado = $this->createNodeCertificado(
            '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" Version="3.3"' . '/>'
        );

        $this->assertEmpty($nodeCertificado->extract());
    }

    public function testExtractWithMalformedBase64()
    {
        $nodeCertificado = $this->createNodeCertificado(
            '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" Version="3.3" Certificado="ñ"' . '/>'
        );

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('The certificado attribute is not a valid base64 encoded string');
        $nodeCertificado->extract();
    }

    public function testExtract()
    {
        $expectedExtract = 'foo';

        $nodeCertificado = $this->createNodeCertificado(
            '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" Version="3.3" Certificado="Zm9v"' . '/>'
        );

        $this->assertSame($expectedExtract, $nodeCertificado->extract());
    }

    public function testSaveWithEmptyFilename()
    {
        $nodeCertificado = $this->createNodeCertificado(
            '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" Version="3.3" Certificado="Zm9v"' . '/>'
        );

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('The filename to store the certificate is empty');
        $nodeCertificado->save('');
    }

    public function testSaveWithEmptyCertificado()
    {
        $nodeCertificado = $this->createNodeCertificado(
            '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" Version="3.3"' . '/>'
        );

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('The certificado attribute is empty');
        $nodeCertificado->save(__DIR__);
    }

    public function testSaveWithUnwritableFilename()
    {
        $nodeCertificado = $this->createNodeCertificado(
            '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" Version="3.3" Certificado="Zm9v"' . '/>'
        );

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unable to write the certificate contents');
        $nodeCertificado->save(__DIR__);
    }

    public function testSave()
    {
        $nodeCertificado = $this->createNodeCertificado(
            '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" Version="3.3" Certificado="Zm9v"' . '/>'
        );

        $tempfile = tempnam('', '');
        $nodeCertificado->save($tempfile);
        $this->assertFileExists($tempfile);
        $this->assertStringEqualsFile($tempfile, 'foo');
        unlink($tempfile);
    }

    public function testObtain()
    {
        $cfdiSample = $this->utilAsset('cfdi32-real.xml');
        $nodeCertificado = $this->createNodeCertificado(
            file_get_contents($cfdiSample)
        );

        $certificate = $nodeCertificado->obtain();
        $this->assertFileNotExists($certificate->getFilename());
        $this->assertEquals('CTO021007DZ8', $certificate->getRfc());
    }
}
