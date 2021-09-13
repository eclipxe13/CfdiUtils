<?php

namespace CfdiUtilsTests\Certificado;

use CfdiUtils\Certificado\NodeCertificado;
use CfdiUtils\Internals\TemporaryFile;
use CfdiUtils\Nodes\XmlNodeUtils;
use CfdiUtilsTests\TestCase;

final class NodeCertificadoTest extends TestCase
{
    private function createNodeCertificado(string $contents): NodeCertificado
    {
        return new NodeCertificado(XmlNodeUtils::nodeFromXmlString($contents));
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

        $temporaryFile = TemporaryFile::create();
        $nodeCertificado->save($temporaryFile->getPath());
        $this->assertStringEqualsFile($temporaryFile->getPath(), 'foo');
        $temporaryFile->remove();
    }

    public function testObtain()
    {
        $cfdiSample = $this->utilAsset('cfdi32-real.xml');
        $nodeCertificado = $this->createNodeCertificado(strval(file_get_contents($cfdiSample)));

        $certificate = $nodeCertificado->obtain();
        $this->assertEquals('CTO021007DZ8', $certificate->rfc());
    }
}
