<?php
namespace CfdiUtilsTests\Certificado;

use CfdiUtils\Certificado\Certificado;
use CfdiUtils\Certificado\CertificadoPropertyInterface;
use CfdiUtils\Certificado\CertificadoPropertyTrait;
use CfdiUtilsTests\TestCase;

class CertificadoPropertyTest extends TestCase
{
    public function testCertificadoProperty()
    {
        $implementation = new class() implements CertificadoPropertyInterface {
            use CertificadoPropertyTrait;
        };

        $this->assertFalse($implementation->hasCertificado());
        $certificado = new Certificado($this->utilAsset('certs/CSD01_AAA010101AAA.cer'));

        $implementation->setCertificado($certificado);
        $this->assertTrue($implementation->hasCertificado());
        $this->assertSame($certificado, $implementation->getCertificado());

        $implementation->setCertificado();
        $this->assertFalse($implementation->hasCertificado());

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('current certificado');
        $implementation->getCertificado();
    }
}
