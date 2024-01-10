<?php

namespace CfdiUtilsTests\Retenciones;

use CfdiUtils\CadenaOrigen\DOMBuilder;
use CfdiUtils\Certificado\Certificado;
use CfdiUtils\Elements\Dividendos10\Dividendos;
use CfdiUtils\Retenciones\RetencionesCreator10;
use CfdiUtilsTests\TestCase;

final class RetencionesCreator10Test extends TestCase
{
    public function testCreatePreCfdiWithAllCorrectValues()
    {
        $cerFile = $this->utilAsset('certs/EKU9003173C9.cer');
        $pemFile = $this->utilAsset('certs/EKU9003173C9.key.pem');
        $passPhrase = '';
        $certificado = new Certificado($cerFile);
        $xmlResolver = $this->newResolver();
        $xsltBuilder = new DOMBuilder();

        // create object
        $creator = new RetencionesCreator10([
            'FechaExp' => '2021-01-13T14:15:16-06:00',
            'CveRetenc' => '14', // Dividendos o utilidades distribuidos
        ], $xmlResolver, $xsltBuilder);
        $retenciones = $creator->retenciones();
        $retenciones->addEmisor([
            'RFCEmisor' => 'EKU9003173C9',
            'NomDenRazSocE' => 'ESCUELA KEMPER URGATE SA DE CV',
        ]);
        $retenciones->getReceptor()->addExtranjero([
            'NumRegIdTrib' => '998877665544332211',
            'NomDenRazSocR' => 'WORLD WIDE COMPANY INC',
        ]);
        $retenciones->addPeriodo(['MesIni' => '5', 'MesFin' => '5', 'Ejerc' => '2018']);
        $retenciones->addTotales([
            'montoTotOperacion' => '55578643',
            'montoTotGrav' => '0',
            'montoTotExent' => '55578643',
            'montoTotRet' => '0',
        ]);
        $retenciones->addImpRetenidos([
            'BaseRet' => '0',
            'Impuesto' => '01', // 01 - ISR
            'montoRet' => '0',
            'TipoPagoRet' => 'Pago provisional',
        ]);

        $dividendos = new Dividendos();
        $dividendos->addDividOUtil([
            'CveTipDivOUtil' => '06', // 06 - Proviene de CUFIN al 31 de diciembre 2013
            'MontISRAcredRetMexico' => '0',
            'MontISRAcredRetExtranjero' => '0',
            'MontRetExtDivExt' => '0',
            'TipoSocDistrDiv' => 'Sociedad Nacional',
            'MontISRAcredNal' => '0',
            'MontDivAcumNal' => '0',
            'MontDivAcumExt' => '0',
        ]);
        $retenciones->addComplemento($dividendos);

        // verify properties
        $this->assertSame($xmlResolver, $creator->getXmlResolver());
        $this->assertSame($xsltBuilder, $creator->getXsltBuilder());

        // verify root node
        $root = $creator->retenciones();
        $this->assertSame('1.0', $root['Version']);

        // put additional content using helpers
        $creator->putCertificado($certificado);
        $creator->addSello('file://' . $pemFile, $passPhrase);

        // move sat definitions
        $creator->moveSatDefinitionsToRetenciones();

        // validate
        $asserts = $creator->validate();
        $this->assertGreaterThanOrEqual(1, $asserts->count());
        $this->assertTrue($asserts->exists('XSD01'));
        $this->assertSame('', $asserts->get('XSD01')->getExplanation());
        $this->assertFalse($asserts->hasErrors());

        // check against known content
        /** @see tests/assets/retenciones/retenciones10.xml */
        $this->assertXmlStringEqualsXmlFile($this->utilAsset('retenciones/retenciones10.xml'), $creator->asXml());
    }

    public function testValidateIsCheckingAgainstXsdViolations()
    {
        $retencion = new RetencionesCreator10();
        $retencion->setXmlResolver($this->newResolver());
        $assert = $retencion->validate()->get('XSD01');
        $this->assertTrue($assert->getStatus()->isError());
    }

    public function testAddSelloFailsWithWrongPassPrase()
    {
        $pemFile = $this->utilAsset('certs/EKU9003173C9_password.key.pem');
        $passPhrase = '_worng_passphrase_';

        $retencion = new RetencionesCreator10();
        $retencion->setXmlResolver($this->newResolver());

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Cannot open the private key');
        $retencion->addSello('file://' . $pemFile, $passPhrase);
    }

    public function testAddSelloFailsWithWrongCertificado()
    {
        $cerFile = $this->utilAsset('certs/CSD09_AAA010101AAA.cer');
        $pemFile = $this->utilAsset('certs/EKU9003173C9.key.pem');
        $passPhrase = '';
        $certificado = new Certificado($cerFile);

        $retencion = new RetencionesCreator10();
        $retencion->setXmlResolver($this->newResolver());

        $retencion->putCertificado($certificado);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('The private key does not belong to the current certificate');
        $retencion->addSello('file://' . $pemFile, $passPhrase);
    }
}
