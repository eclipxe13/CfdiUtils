<?php
namespace CfdiUtilsTests\Retenciones;

use CfdiUtils\CadenaOrigen\DOMBuilder;
use CfdiUtils\Certificado\Certificado;
use CfdiUtils\Nodes\Node;
use CfdiUtils\Retenciones\Retenciones33;
use CfdiUtilsTests\TestCase;

class Retenciones33Test extends TestCase
{
    public function testCreatePreCfdiWithAllCorrectValues()
    {
        $cerFile = $this->utilAsset('certs/CSD01_AAA010101AAA.cer');
        $pemFile = $this->utilAsset('certs/CSD01_AAA010101AAA.key.pem');
        $passPhrase = '';
        $certificado = new Certificado($cerFile);
        $xmlResolver = $this->newResolver();
        $xsltBuilder = new DOMBuilder();

        // create object
        $retencion = new Retenciones33([
            'FechaExp' => '2019-01-23T08:00:00-06:00',
            'CveRetenc' => '14', // Dividendos o utilidades distribuidos
        ], [
            new Node('retenciones:Emisor', [
                'RFCEmisor' => 'AAA010101AAA',
                'NomDenRazSocE' => 'ACCEM SERVICIOS EMPRESARIALES SC',
            ]),
            new Node('retenciones:Receptor', [
                'Nacionalidad' => 'Extranjero',
            ], [
                new Node('retenciones:Extranjero', [
                    'NumRegIdTrib' => '998877665544332211',
                    'NomDenRazSocR' => 'WORLD WIDE COMPANY INC',
                ]),
            ]),
            new Node('retenciones:Periodo', ['MesIni' => '5', 'MesFin' => '5', 'Ejerc' => '2018']),
            new Node('retenciones:Totales', [
                'montoTotOperacion' => '55578643',
                'montoTotGrav' => '0',
                'montoTotExent' => '55578643',
                'montoTotRet' => '0',
            ], [
                new Node('retenciones:ImpRetenidos', [
                    'BaseRet' => '0',
                    'Impuesto' => '01', // 01 - ISR
                    'montoRet' => '0',
                    'TipoPagoRet' => 'Pago provisional',
                ]),
            ]),
            new Node('retenciones:Complemento', [], [
                new Node('dividendos:Dividendos', [
                    'xmlns:dividendos' => 'http://www.sat.gob.mx/esquemas/retencionpago/1/dividendos',
                    'xsi:schemaLocation' => vsprintf('%s %s', [
                        'http://www.sat.gob.mx/esquemas/retencionpago/1/dividendos',
                        'http://www.sat.gob.mx/esquemas/retencionpago/1/dividendos/dividendos.xsd',
                    ]),
                    'Version' => '1.0',
                ], [
                    new Node('dividendos:DividOUtil', [
                        'CveTipDivOUtil' => '06', // 06 - Proviene de CUFIN al 31 de diciembre 2013
                        'MontISRAcredRetMexico' => '0',
                        'MontISRAcredRetExtranjero' => '0',
                        'MontRetExtDivExt' => '0',
                        'TipoSocDistrDiv' => 'Sociedad Nacional',
                        'MontISRAcredNal' => '0',
                        'MontDivAcumNal' => '0',
                        'MontDivAcumExt' => '0',
                    ]),
                ]),
            ]),
        ], $xmlResolver, $xsltBuilder);

        // verify properties
        $this->assertSame($xmlResolver, $retencion->getXmlResolver());
        $this->assertSame($xsltBuilder, $retencion->getXsltBuilder());

        // verify root node
        $root = $retencion->rootNode();
        $this->assertSame(Retenciones33::XMLNS_1_0, $root['xmlns:retenciones']);
        $this->assertSame(Retenciones33::XMLNS_1_0 . ' ' . Retenciones33::XSD_1_0, $root['xsi:schemaLocation']);
        $this->assertSame('1.0', $root['Version']);

        // put additional content using helpers
        $retencion->putCertificado($certificado);
        $retencion->addSello('file://' . $pemFile, $passPhrase);

        // validate
        $asserts = $retencion->validate();
        $this->assertGreaterThanOrEqual(1, $asserts->count());
        $this->assertTrue($asserts->exists('XSD01'));
        $this->assertSame('', $asserts->get('XSD01')->getExplanation());
        $this->assertFalse($asserts->hasErrors());

        // check against known content
        $this->assertXmlStringEqualsXmlFile($this->utilAsset('retenciones/sample-before-tfd.xml'), $retencion->asXml());
    }

    public function testValidateIsCheckingAgainstXsdViolations()
    {
        $retencion = new Retenciones33();
        $retencion->setXmlResolver($this->newResolver());
        $assert = $retencion->validate()->get('XSD01');
        $this->assertTrue($assert->getStatus()->isError());
    }

    public function testAddSelloFailsWithWrongPassPrase()
    {
        $pemFile = $this->utilAsset('certs/CSD01_AAA010101AAA_password.key.pem');
        $passPhrase = '_worng_passphrase_';

        $retencion = new Retenciones33();
        $retencion->setXmlResolver($this->newResolver());

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Cannot open the private key');
        $retencion->addSello('file://' . $pemFile, $passPhrase);
    }

    public function testAddSelloFailsWithWrongCertificado()
    {
        $cerFile = $this->utilAsset('certs/CSD09_AAA010101AAA.cer');
        $pemFile = $this->utilAsset('certs/CSD01_AAA010101AAA.key.pem');
        $passPhrase = '';
        $certificado = new Certificado($cerFile);

        $retencion = new Retenciones33();
        $retencion->setXmlResolver($this->newResolver());

        $retencion->putCertificado($certificado);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('The private key does not belong to the current certificate');
        $retencion->addSello('file://' . $pemFile, $passPhrase);
    }
}
