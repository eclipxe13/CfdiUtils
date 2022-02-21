<?php

namespace CfdiUtilsTests\Validate\Cfdi40\Standard;

use CfdiUtils\Validate\Cfdi40\Standard\SelloDigitalCertificado;
use CfdiUtilsTests\Validate\Common\SelloDigitalCertificadoWithCfdiRegistroFiscalTrait;
use CfdiUtilsTests\Validate\Validate40TestCase;

final class SelloDigitalCertificadoWithCfdiRegistroFiscalTest extends Validate40TestCase
{
    use SelloDigitalCertificadoWithCfdiRegistroFiscalTrait;

    /** @var SelloDigitalCertificado */
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new SelloDigitalCertificado();
        $this->hydrater->hydrate($this->validator);

        $cerfile = $this->utilAsset('certs/00001000000403258748.cer');
        $this->setUpCertificado([], [
            'Nombre' => 'CARLOS CORTES SOTO',
            'Rfc' => 'COSC8001137NA',
        ], $cerfile);
    }
}
