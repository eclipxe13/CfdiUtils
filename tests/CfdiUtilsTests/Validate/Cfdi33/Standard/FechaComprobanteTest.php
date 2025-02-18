<?php

namespace CfdiUtilsTests\Validate\Cfdi33\Standard;

use CfdiUtils\Utils\Format;
use CfdiUtils\Validate\Cfdi33\Standard\FechaComprobante;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\Validate\Validate33TestCase;

final class FechaComprobanteTest extends Validate33TestCase
{
    /** @var FechaComprobante */
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new FechaComprobante();
    }

    public function testConstructWithoutArguments(): void
    {
        $expectedTolerance = 300;
        $expectedMaxTime = time() + $expectedTolerance;

        $validator = new FechaComprobante();

        $this->assertTrue($validator->canValidateCfdiVersion('3.3'));
        $this->assertEquals($expectedMaxTime, $validator->getMaximumDate());
        $this->assertEquals($expectedTolerance, $validator->getTolerance());
    }

    public function testSetMaximumDate(): void
    {
        $validator = new FechaComprobante();
        $validator->setMaximumDate(0);
        $this->assertEquals(0, $validator->getMaximumDate());
    }

    public function testSetTolerance(): void
    {
        $validator = new FechaComprobante();
        $validator->setTolerance(1000);
        $this->assertEquals(1000, $validator->getTolerance());
    }

    public function testValidateOkCurrentDate(): void
    {
        $timestamp = time();
        $this->comprobante->addAttributes([
            'Fecha' => Format::datetime($timestamp),
        ]);

        $this->runValidate();

        $this->assertStatusEqualsCode(Status::ok(), 'FECHA01');
        $this->assertStatusEqualsCode(Status::ok(), 'FECHA02');
        $this->assertFalse($this->asserts->hasErrors());
    }

    public function testValidateOkMinimumDate(): void
    {
        $timestamp = $this->validator->getMinimumDate();
        $this->comprobante->addAttributes([
            'Fecha' => Format::datetime($timestamp),
        ]);

        $this->runValidate();

        $this->assertStatusEqualsCode(Status::ok(), 'FECHA01');
        $this->assertStatusEqualsCode(Status::ok(), 'FECHA02');
        $this->assertFalse($this->asserts->hasErrors());
    }

    public function testValidateOkMaximumDate(): void
    {
        $timestamp = $this->validator->getMaximumDate();
        $this->comprobante->addAttributes([
            'Fecha' => Format::datetime($timestamp),
        ]);

        $this->runValidate();

        $this->assertStatusEqualsCode(Status::ok(), 'FECHA01');
        $this->assertStatusEqualsCode(Status::ok(), 'FECHA02');
        $this->assertFalse($this->asserts->hasErrors());
    }

    public function testValidateWithoutFecha(): void
    {
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'FECHA01');
        $this->assertStatusEqualsCode(Status::none(), 'FECHA02');
    }

    public function testValidateEmptyFecha(): void
    {
        $this->comprobante->addAttributes([
            'Fecha' => '',
        ]);

        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'FECHA01');
        $this->assertStatusEqualsCode(Status::none(), 'FECHA02');
    }

    public function testValidateMalformedFecha(): void
    {
        $this->comprobante->addAttributes([
            'Fecha' => 'YYYY-MM-DD hh:mm:ss',
        ]);

        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'FECHA01');
        $this->assertStatusEqualsCode(Status::none(), 'FECHA02');
    }

    public function testValidateOlderFecha(): void
    {
        $this->comprobante->addAttributes([
            'Fecha' => '2017-06-30T23:59:59',
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::ok(), 'FECHA01');
        $this->assertStatusEqualsCode(Status::error(), 'FECHA02');
    }

    public function testValidateFutureFecha(): void
    {
        $this->comprobante->addAttributes([
            'Fecha' => Format::datetime($this->validator->getMaximumDate() + 1),
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::ok(), 'FECHA01');
        $this->assertStatusEqualsCode(Status::error(), 'FECHA02');
    }
}
