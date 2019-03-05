<?php
namespace CfdiUtilsTests\TimbreFiscalDigital;

use CfdiUtils\Cfdi;
use CfdiUtils\Nodes\XmlNodeUtils;
use CfdiUtils\TimbreFiscalDigital\TfdCadenaDeOrigen;
use CfdiUtils\XmlResolver\XmlResolver;
use CfdiUtilsTests\TestCase;

class TfdCadenaDeOrigenTest extends TestCase
{
    public function testConstructorMinimal()
    {
        $tfdCO = new TfdCadenaDeOrigen();
        $this->assertInstanceOf(XmlResolver::class, $tfdCO->getXmlResolver());
    }

    public function testConstructorWithXmlResolver()
    {
        $resolver = $this->newResolver();
        $tfdCO = new TfdCadenaDeOrigen($resolver);
        $this->assertSame($resolver, $tfdCO->getXmlResolver());
    }

    public function testObtainVersion11WithoutVersionArgument()
    {
        $cfdi = Cfdi::newFromString(strval(file_get_contents($this->utilAsset('cfdi33-valid.xml'))));
        $tfd = $cfdi->getNode()->searchNode('cfdi:Complemento', 'tfd:TimbreFiscalDigital');
        if (null === $tfd) {
            $this->fail('Cannot get the tfd:TimbreFiscalDigital node');
            return;
        }
        $tfdXml = XmlNodeUtils::nodeToXmlString($tfd);

        $tfdCO = new TfdCadenaDeOrigen();
        $cadenaOrigen = $tfdCO->build($tfdXml);

        $expected = '||' . str_replace('||', '|', implode('|', [
            $tfd['Version'],
            $tfd['UUID'],
            $tfd['FechaTimbrado'],
            $tfd['RfcProvCertif'],
            $tfd['Leyenda'],
            $tfd['SelloCFD'],
            $tfd['NoCertificadoSAT'],
        ])) . '||';

        $this->assertSame($expected, $cadenaOrigen);
    }

    public function testXsltLocation()
    {
        $this->assertContains('TFD_1_0.xslt', TfdCadenaDeOrigen::xsltLocation('1.0'));
        $this->assertContains('TFD_1_1.xslt', TfdCadenaDeOrigen::xsltLocation('1.1'));
    }

    public function testXsltLocationException()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Cannot get the xslt location');

        TfdCadenaDeOrigen::xsltLocation('');
    }
}
