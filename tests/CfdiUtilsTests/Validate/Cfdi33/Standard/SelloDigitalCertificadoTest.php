<?php

namespace CfdiUtilsTests\Validate\Cfdi33\Standard;

use CfdiUtils\Validate\Cfdi33\Standard\SelloDigitalCertificado;
use CfdiUtils\Validate\Contracts\ValidatorInterface;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\Validate\Common\SelloDigitalCertificadoWithRegularCertificadoTrait;
use CfdiUtilsTests\Validate\Validate33TestCase;

final class SelloDigitalCertificadoTest extends Validate33TestCase
{
    use SelloDigitalCertificadoWithRegularCertificadoTrait;

    /** @var SelloDigitalCertificado */
    protected ValidatorInterface $validator;

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

    public function testValidateBadSello(): void
    {
        $this->setupCfdiFile('cfdi33-valid.xml');
        $this->comprobante['Sello'] = $this->comprobante['Certificado'];
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'SELLO08');
    }

    public function testValidateOk(): void
    {
        $this->setupCfdiFile('cfdi33-valid.xml');
        $this->runValidate();
        foreach (range(1, 8) as $i) {
            $this->assertStatusEqualsCode(Status::ok(), 'SELLO0' . $i);
        }
        $this->assertCount(8, $this->asserts, 'All 8 were are tested');
    }

    public function testValidateWithEqualButNotIdenticalName(): void
    {
        //    change case, and punctuation to original name
        //                   ESCUELA KEMPER URGATE SA DE CV
        $this->setUpCertificado([], [
            'Nombre' => 'ESCUELA "Kemper Urgate", S.A. DE C.V.',
        ]);

        $this->runValidate();

        $this->assertStatusEqualsCode(Status::ok(), 'SELLO04');
    }
}
