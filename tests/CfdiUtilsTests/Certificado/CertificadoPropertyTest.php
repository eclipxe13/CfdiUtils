<?php

namespace CfdiUtilsTests\Certificado;

use CfdiUtils\Certificado\CertificadoPropertyInterface;
use CfdiUtils\Certificado\CertificadoPropertyTrait;
use CfdiUtilsTests\TestCase;
use PhpCfdi\Credentials\Certificate;

final class CertificadoPropertyTest extends TestCase
{
    public function testCertificadoProperty()
    {
        $implementation = new class() implements CertificadoPropertyInterface {
            use CertificadoPropertyTrait;
        };

        $certificado = Certificate::openFile($this->utilAsset('certs/EKU9003173C9.cer'));

        $implementation->setCertificado($certificado);
        $this->assertSame($certificado, $implementation->getCertificado());
    }

    public function testCertificadoPropertyWithoutInitialization()
    {
        $implementation = new class() implements CertificadoPropertyInterface {
            use CertificadoPropertyTrait;
        };

        $this->expectException(\TypeError::class);
        $implementation->getCertificado();
    }
}
