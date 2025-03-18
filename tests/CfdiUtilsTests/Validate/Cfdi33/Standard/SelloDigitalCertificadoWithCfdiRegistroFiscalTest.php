<?php

namespace CfdiUtilsTests\Validate\Cfdi33\Standard;

use CfdiUtils\Validate\Cfdi33\Standard\SelloDigitalCertificado;
use CfdiUtils\Validate\Contracts\ValidatorInterface;
use CfdiUtilsTests\Validate\Common\SelloDigitalCertificadoWithCfdiRegistroFiscalTrait;
use CfdiUtilsTests\Validate\Validate33TestCase;

final class SelloDigitalCertificadoWithCfdiRegistroFiscalTest extends Validate33TestCase
{
    use SelloDigitalCertificadoWithCfdiRegistroFiscalTrait;

    /** @var SelloDigitalCertificado */
    protected ValidatorInterface $validator;

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
