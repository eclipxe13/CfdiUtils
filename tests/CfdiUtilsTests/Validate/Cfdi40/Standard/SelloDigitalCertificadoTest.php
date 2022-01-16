<?php

namespace CfdiUtilsTests\Validate\Cfdi40\Standard;

use CfdiUtils\Validate\Cfdi40\Standard\SelloDigitalCertificado;
use CfdiUtilsTests\Validate\Common\SelloDigitalCertificadoWithRegularCertificadoTrait;
use CfdiUtilsTests\Validate\Validate40TestCase;

final class SelloDigitalCertificadoTest extends Validate40TestCase
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
        $this->assertTrue($this->validator->canValidateCfdiVersion('4.0'));
    }
}
