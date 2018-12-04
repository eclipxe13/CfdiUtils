<?php
namespace CfdiUtilsTests;

use CfdiUtils\Certificado\Certificado;
use CfdiUtils\CfdiCreator33;
use CfdiUtils\Nodes\XmlNodeUtils;

class CfdiCreatorFromExistentTest extends TestCase
{
    public function testNewUsingNode()
    {
        $xmlSource = file_get_contents($this->utilAsset('cfdi33-real.xml'));
        $nodeSource = XmlNodeUtils::nodeFromXmlString($xmlSource);
        $creator = CfdiCreator33::newUsingNode($nodeSource);
        $this->assertXmlStringEqualsXmlString($xmlSource, $creator->asXml());
    }

    public function testNewImportingNode()
    {
        $xmlSource = file_get_contents($this->utilAsset('cfdi33-real.xml'));
        $nodeSource = XmlNodeUtils::nodeFromXmlString($xmlSource);
        $creator = CfdiCreator33::newUsingNode($nodeSource);
        $this->assertXmlStringEqualsXmlString($xmlSource, $creator->asXml());
    }

    public function testPutCertificadoFromCreatorUsingNode()
    {
        $xmlSource = file_get_contents($this->utilAsset('cfdi33-real.xml')) ?: '';
        $nodeSource = XmlNodeUtils::nodeFromXmlString($xmlSource);
        $creator = CfdiCreator33::newUsingNode($nodeSource);
        $creator->putCertificado(new Certificado($this->utilAsset('certs/CSD01_AAA010101AAA.cer')), true);

        $comprobante = $creator->comprobante();
        $this->assertCount(1, $comprobante->searchNodes('cfdi:Emisor'));
        $this->assertSame('ACCEM SERVICIOS EMPRESARIALES SC', $comprobante->searchAttribute('cfdi:Emisor', 'Nombre'));
        $this->assertSame('AAA010101AAA', $comprobante->searchAttribute('cfdi:Emisor', 'Rfc'));
    }
}
