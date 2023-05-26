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
        $expected = [
            'SELLO01' => Status::ok(),
            'SELLO02' => Status::ok(),
            'SELLO03' => Status::ok(),
            'SELLO04' => Status::none(),
            'SELLO05' => Status::ok(),
            'SELLO06' => Status::ok(),
            'SELLO07' => Status::ok(),
            'SELLO08' => Status::ok(),
        ];
        foreach ($expected as $code => $status) {
            $this->assertStatusEqualsCode($status, $code);
        }
        $this->assertCount(8, $this->asserts, 'All 8 were are tested');
    }

    public function testValidateNameEmpty()
    {
        $this->setUpCertificado([], [
            'Nombre' => '',
        ]);

        $this->runValidate();

        $status = $this->getAssertByCodeOrFail('SELLO04');

        $this->assertTrue($status->getStatus()->isError());
        $this->assertSame('Nombre del emisor vacío', $status->getExplanation());
    }

    public function testValidateNameMoralPerson()
    {
        $this->setUpCertificado([], [
            'Nombre' => 'ESCUELA KEMPER URGATE SA DE CV',
        ]);

        $this->runValidate();

        $status = $this->getAssertByCodeOrFail('SELLO04');

        $this->assertTrue($status->getStatus()->isNone());
        $this->assertSame('No es posible realizar la validación en Personas Morales', $status->getExplanation());
    }

    public function testValidateWithIdenticalNameRegularPerson()
    {
        $this->setUpCertificado([], [
            'Rfc' => 'COSC8001137NA', // set as persona física to force name comparison
            'Nombre' => 'ESCUELA KEMPER URGATE SA DE CV',
        ]);

        $this->runValidate();

        $this->assertStatusEqualsCode(Status::ok(), 'SELLO04');
    }

    public function testValidateWithoutIdenticalNameRegularPerson()
    {
        $this->setUpCertificado([], [
            'Rfc' => 'COSC8001137NA',  // set as persona física to force name comparison
            'Nombre' => 'ESCUELA KEMPER URGATE SA DE CV ',
        ]);

        $this->runValidate();

        $status = $this->getAssertByCodeOrFail('SELLO04');

        $this->assertTrue($status->getStatus()->isError());
        $this->assertSame(
            'Nombre certificado: ESCUELA KEMPER URGATE SA DE CV, Nombre comprobante: ESCUELA KEMPER URGATE SA DE CV .',
            $status->getExplanation()
        );
    }
}
