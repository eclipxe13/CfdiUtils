<?php

namespace CfdiUtilsTests;

use CfdiUtils\Certificado\Certificado;
use CfdiUtils\CfdiCreator33;
use CfdiUtils\Nodes\XmlNodeUtils;

final class CfdiCreatorFromExistentTest extends TestCase
{
    public function testNewUsingNode()
    {
        $xmlSource = strval(file_get_contents($this->utilAsset('cfdi33-real.xml')));
        $nodeSource = XmlNodeUtils::nodeFromXmlString($xmlSource);
        $creator = CfdiCreator33::newUsingNode($nodeSource);
        $this->assertXmlStringEqualsXmlString($xmlSource, $creator->asXml());
    }

    public function testNewImportingNode()
    {
        $xmlSource = strval(file_get_contents($this->utilAsset('cfdi33-real.xml')));
        $nodeSource = XmlNodeUtils::nodeFromXmlString($xmlSource);
        $creator = CfdiCreator33::newUsingNode($nodeSource);
        $this->assertXmlStringEqualsXmlString($xmlSource, $creator->asXml());
    }

    public function testPutCertificadoFromCreatorUsingNode()
    {
        $xmlSource = strval(file_get_contents($this->utilAsset('cfdi33-real.xml')));
        $nodeSource = XmlNodeUtils::nodeFromXmlString($xmlSource);
        $creator = CfdiCreator33::newUsingNode($nodeSource);
        $creator->putCertificado(new Certificado($this->utilAsset('certs/EKU9003173C9.cer')), true);

        $comprobante = $creator->comprobante();
        $this->assertCount(1, $comprobante->searchNodes('cfdi:Emisor'));
        $this->assertSame('ESCUELA KEMPER URGATE SA DE CV', $comprobante->searchAttribute('cfdi:Emisor', 'Nombre'));
        $this->assertSame('EKU9003173C9', $comprobante->searchAttribute('cfdi:Emisor', 'Rfc'));
    }
}
