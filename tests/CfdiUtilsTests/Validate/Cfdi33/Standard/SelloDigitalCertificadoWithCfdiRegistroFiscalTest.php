<?php

namespace CfdiUtilsTests\Validate\Cfdi33\Standard;

use CfdiUtils\Certificado\Certificado;
use CfdiUtils\CfdiCreator33;
use CfdiUtils\Elements\Cfdi33\Comprobante;
use CfdiUtils\Elements\Tfd11\TimbreFiscalDigital;
use CfdiUtils\Nodes\Node;
use CfdiUtils\Validate\Cfdi33\Standard\SelloDigitalCertificado;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\Validate\ValidateTestCase;

final class SelloDigitalCertificadoWithCfdiRegistroFiscalTest extends ValidateTestCase
{
    /** @var SelloDigitalCertificado */
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new SelloDigitalCertificado();
        $this->hydrater->hydrate($this->validator);

        $cerfile = $this->utilAsset('certs/00001000000403258748.cer');
        $certificado = new Certificado($cerfile);
        $cfdiCreator = new CfdiCreator33([], $certificado);
        $this->comprobante = $cfdiCreator->comprobante();

        $emisor = $this->comprobante->searchNode('cfdi:Emisor');
        $emisor['Nombre'] = 'CARLOS CORTES SOTO';
        $emisor['Rfc'] = 'COSC8001137NA';
    }

    public function testFailWhenHasNotCfdiRegistroFiscalAndCertificadosDoNotMatch()
    {
        $this->runValidate();

        $this->assertStatusEqualsCode(Status::error(), 'SELLO03');
        $this->assertStatusEqualsCode(Status::error(), 'SELLO04');
    }

    public function testFailWhenHasNotCfdiRegistroFiscalAndCertificadosMatch()
    {
        /** @var Comprobante $comprobante */
        $comprobante = $this->comprobante;

        $comprobante->addComplemento(
            new TimbreFiscalDigital(['NoCertificadoSAT' => '00001000000403258748'])
        );

        $this->runValidate();

        $this->assertStatusEqualsCode(Status::error(), 'SELLO03');
        $this->assertStatusEqualsCode(Status::error(), 'SELLO04');
    }

    public function testFailWhenHasCfdiRegistroFiscalAndCertificadosDoNotMatch()
    {
        /** @var Comprobante $comprobante */
        $comprobante = $this->comprobante;

        $comprobante->addComplemento(
            new Node('registrofiscal:CFDIRegistroFiscal')
        );

        $this->runValidate();

        $this->assertStatusEqualsCode(Status::error(), 'SELLO03');
        $this->assertStatusEqualsCode(Status::error(), 'SELLO04');
    }

    public function testPassWhenHasCfdiRegistroFiscalAndCertificadosMatch()
    {
        /** @var Comprobante $comprobante */
        $comprobante = $this->comprobante;

        $comprobante->addComplemento(
            new Node('registrofiscal:CFDIRegistroFiscal')
        );

        $comprobante->addComplemento(
            new TimbreFiscalDigital(['NoCertificadoSAT' => '00001000000403258748'])
        );

        $this->runValidate();

        $this->assertStatusEqualsCode(Status::none(), 'SELLO03');
        $this->assertStatusEqualsCode(Status::none(), 'SELLO04');
    }
}
