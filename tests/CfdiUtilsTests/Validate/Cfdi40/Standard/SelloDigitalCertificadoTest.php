<?php

namespace CfdiUtilsTests\Validate\Cfdi40\Standard;

use CfdiUtils\Validate\Cfdi40\Standard\SelloDigitalCertificado;
use CfdiUtils\Validate\Status;
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

    public function testValidateBadSello(): void
    {
        $this->setupCfdiFile('cfdi40-valid.xml');
        $this->comprobante['Sello'] = $this->comprobante['Certificado'];
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'SELLO08');
    }

    public function testValidateOk(): void
    {
        $this->setupCfdiFile('cfdi40-valid.xml');
        $this->runValidate();
        foreach (range(1, 8) as $i) {
            $this->assertStatusEqualsCode(Status::ok(), 'SELLO0' . $i);
        }
        $this->assertCount(8, $this->asserts, 'All 8 were are tested');
    }
}
