<?php

namespace CfdiUtilsTests;

use CfdiUtils\Certificado\Certificado;
use CfdiUtils\CfdiCreator40;
use CfdiUtils\Nodes\XmlNodeUtils;

final class CfdiCreator40FromExistentTest extends TestCase
{
    public function testNewUsingNode(): void
    {
        $xmlSource = strval(file_get_contents($this->utilAsset('cfdi40-real.xml')));
        $nodeSource = XmlNodeUtils::nodeFromXmlString($xmlSource);
        $creator = CfdiCreator40::newUsingNode($nodeSource);
        $this->assertXmlStringEqualsXmlString($xmlSource, $creator->asXml());
    }

    public function testNewImportingNode(): void
    {
        $xmlSource = strval(file_get_contents($this->utilAsset('cfdi40-real.xml')));
        $nodeSource = XmlNodeUtils::nodeFromXmlString($xmlSource);
        $creator = CfdiCreator40::newUsingNode($nodeSource);
        $this->assertXmlStringEqualsXmlString($xmlSource, $creator->asXml());
    }

    public function testPutCertificadoFromCreatorUsingNode(): void
    {
        $xmlSource = strval(file_get_contents($this->utilAsset('cfdi40-real.xml')));
        $nodeSource = XmlNodeUtils::nodeFromXmlString($xmlSource);
        $creator = CfdiCreator40::newUsingNode($nodeSource);
        $creator->putCertificado(new Certificado($this->utilAsset('certs/EKU9003173C9.cer')));

        $comprobante = $creator->comprobante();
        $this->assertCount(1, $comprobante->searchNodes('cfdi:Emisor'));
        $this->assertSame('ESCUELA KEMPER URGATE', $comprobante->searchAttribute('cfdi:Emisor', 'Nombre'));
        $this->assertSame('EKU9003173C9', $comprobante->searchAttribute('cfdi:Emisor', 'Rfc'));
    }
}
