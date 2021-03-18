<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Elements\Pagos10\Pago;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\FormaDePago;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\ValidatePagoException;
use PHPUnit\Framework\TestCase;

final class FormaDePagoTest extends TestCase
{
    public function testValid()
    {
        $pago = new Pago([
            'FormaDePagoP' => '23',
        ]);

        $validator = new FormaDePago();
        $this->assertTrue($validator->validatePago($pago));
    }

    /**
     * @param string|null $formaPago
     * @testWith [null]
     *           [""]
     *           ["99"]
     */
    public function testInvalid(?string $formaPago)
    {
        $pago = new Pago([
            'FormaDePagoP' => $formaPago,
        ]);

        $this->expectException(ValidatePagoException::class);

        $validator = new FormaDePago();
        $validator->validatePago($pago);
    }
}
