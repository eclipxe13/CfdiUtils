<?php

namespace CfdiUtilsTests\Validate\Cfdi33\Standard;

use CfdiUtils\Validate\Cfdi33\Standard\SelloDigitalCertificado;
use CfdiUtilsTests\Validate\Common\SelloDigitalCertificadoWithRegularCertificadoTrait;
use CfdiUtilsTests\Validate\Validate33TestCase;

final class SelloDigitalCertificadoTest extends Validate33TestCase
{
    use SelloDigitalCertificadoWithRegularCertificadoTrait;

    /** @var SelloDigitalCertificado */
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new SelloDigitalCertificado();
        $this->hydrater->hydrate($this->validator);
    }

    public function testObjectVersion(): void
    {
        $this->assertTrue($this->validator->canValidateCfdiVersion('3.3'));
    }
}
