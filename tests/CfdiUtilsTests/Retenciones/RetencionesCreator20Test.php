<?php

namespace CfdiUtilsTests\Retenciones;

use CfdiUtils\CadenaOrigen\DOMBuilder;
use CfdiUtils\Certificado\Certificado;
use CfdiUtils\Elements\Dividendos10\Dividendos;
use CfdiUtils\Retenciones\RetencionesCreator20;
use CfdiUtilsTests\TestCase;

final class RetencionesCreator20Test extends TestCase
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
        $creator = new RetencionesCreator20([
            'FechaExp' => '2022-01-13T14:15:16',
            'CveRetenc' => '14', // Dividendos o utilidades distribuidos
            'LugarExpRetenc' => '91778',
        ], $xmlResolver, $xsltBuilder);
        $retenciones = $creator->retenciones();

        // available on RET 2.0
        $retenciones->addCfdiRetenRelacionados([
            'TipoRelacion' => '01',
            'UUID' => '1474b7d3-61fc-41c4-a8b8-3f22e1161bb4',
        ]);
        $retenciones->addEmisor([
            'RfcE' => 'EKU9003173C9',
            'NomDenRazSocE' => 'ESCUELA KEMPER URGATE',
            'RegimenFiscalE' => '601',
        ]);
        $retenciones->getReceptor()->addExtranjero([
            'NumRegIdTribR' => '998877665544332211',
            'NomDenRazSocR' => 'WORLD WIDE COMPANY INC',
        ]);
        $retenciones->addPeriodo(['MesIni' => '05', 'MesFin' => '05', 'Ejercicio' => '2021']);
        $retenciones->addTotales([
            'MontoTotOperacion' => '55578643',
            'MontoTotGrav' => '0',
            'MontoTotExent' => '55578643',
            'MontoTotRet' => '0',
            'UtilidadBimestral' => '0.1',
            'ISRCorrespondiente' => '0.1',
        ]);
        $retenciones->addImpRetenidos([
            'BaseRet' => '0',
            'ImpuestoRet' => '001', // same as CFDI
            'TipoPagoRet' => '01',
            'MontoRet' => '200.00',
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
        $this->assertSame('2.0', $root['Version']);

        // put additional content using helpers
        $creator->putCertificado($certificado);
        $creator->addSello('file://' . $pemFile, $passPhrase);

        // validate
        $asserts = $creator->validate();
        $this->assertGreaterThanOrEqual(1, $asserts->count());
        $this->assertTrue($asserts->exists('XSD01'));
        $this->assertSame('', $asserts->get('XSD01')->getExplanation());
        $this->assertFalse($asserts->hasErrors());

        // check against known content
        /** @see tests/assets/retenciones/retenciones20.xml */
        $this->assertXmlStringEqualsXmlFile($this->utilAsset('retenciones/retenciones20.xml'), $creator->asXml());
    }

    public function testValidateIsCheckingAgainstXsdViolations()
    {
        $retencion = new RetencionesCreator20();
        $retencion->setXmlResolver($this->newResolver());
        $assert = $retencion->validate()->get('XSD01');
        $this->assertTrue($assert->getStatus()->isError());
    }

    public function testAddSelloFailsWithWrongPassPrase()
    {
        $pemFile = $this->utilAsset('certs/EKU9003173C9_password.key.pem');
        $passPhrase = '_worng_passphrase_';

        $retencion = new RetencionesCreator20();
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

        $retencion = new RetencionesCreator20();
        $retencion->setXmlResolver($this->newResolver());

        $retencion->putCertificado($certificado);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('The private key does not belong to the current certificate');
        $retencion->addSello('file://' . $pemFile, $passPhrase);
    }
}
