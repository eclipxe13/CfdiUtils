<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Elements\Pagos10\Pago;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\CuentaBeneficiariaPatron;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\ValidatePagoException;
use PHPUnit\Framework\TestCase;

class CuentaBeneficiariaPatronTest extends TestCase
{
    /**
     * @param string $input
     * @testWith ["1234567890123456"]
     *           [null]
     */
    public function testValid($input)
    {
        $pago = new Pago([
            'FormaDePagoP' => '04', // require a pattern of 16 digits
            'CtaBeneficiario' => $input,
        ]);
        $validator = new CuentaBeneficiariaPatron();
        $this->assertTrue($validator->validatePago($pago));
    }

    /**
     * @param string $input
     * @testWith ["1"]
     *           [""]
     */
    public function testInvalid($input)
    {
        $pago = new Pago([
            'FormaDePagoP' => '04', // require a pattern of 16 digits
            'CtaBeneficiario' => $input,
        ]);
        $validator = new CuentaBeneficiariaPatron();

        $this->expectException(ValidatePagoException::class);
        $validator->validatePago($pago);
    }
}
