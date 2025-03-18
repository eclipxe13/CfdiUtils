<?php

namespace CfdiUtilsTests\Validate\Cfdi40\Standard;

use CfdiUtils\Validate\Cfdi40\Standard\SelloDigitalCertificado;
use CfdiUtils\Validate\Contracts\ValidatorInterface;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\Validate\Common\SelloDigitalCertificadoWithRegularCertificadoTrait;
use CfdiUtilsTests\Validate\Validate40TestCase;

final class SelloDigitalCertificadoTest extends Validate40TestCase
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
            'SELLO04' => Status::ok(),
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

    public function testValidateNameEmpty(): void
    {
        $this->setUpCertificado([], [
            'Nombre' => '',
        ]);

        $this->runValidate();

        $status = $this->getAssertByCodeOrFail('SELLO04');

        $this->assertTrue($status->getStatus()->isError());
        $this->assertSame('Nombre del emisor vacío', $status->getExplanation());
    }

    public function testValidateNameMoralPerson(): void
    {
        $this->setUpCertificado([], [
            'Nombre' => 'ESCUELA KEMPER URGATE',
        ]);

        $this->runValidate();

        $status = $this->getAssertByCodeOrFail('SELLO04');

        $this->assertTrue($status->getStatus()->isOk());
        $this->assertSame(
            'Nombre certificado: ESCUELA KEMPER URGATE SA DE CV, Nombre comprobante: ESCUELA KEMPER URGATE.',
            $status->getExplanation()
        );
    }

    public function testValidateWithIdenticalNameRegularPerson(): void
    {
        $this->setUpCertificado([], [
            'Rfc' => 'COSC8001137NA', // set as persona física to force name comparison and not remove suffix
            'Nombre' => 'ESCUELA KEMPER URGATE SA DE CV',
        ]);

        $this->runValidate();

        $this->assertStatusEqualsCode(Status::ok(), 'SELLO04');
    }

    public function testValidateWithoutIdenticalNameRegularPerson(): void
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
