<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Elements\Pagos10\Pago;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\CuentaOrdenantePatron;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\ValidatePagoException;
use PHPUnit\Framework\TestCase;

final class CuentaOrdenantePatronTest extends TestCase
{
    /**
     * @testWith ["1234567890123456"]
     *           [null]
     */
    public function testValid(?string $input): void
    {
        $pago = new Pago([
            'FormaDePagoP' => '04', // require a pattern of 16 digits
            'CtaOrdenante' => $input,
        ]);
        $validator = new CuentaOrdenantePatron();
        $this->assertTrue($validator->validatePago($pago));
    }

    /**
     * @testWith ["1"]
     *           [""]
     */
    public function testInvalid(string $input): void
    {
        $pago = new Pago([
            'FormaDePagoP' => '04', // require a pattern of 16 digits
            'CtaOrdenante' => $input,
        ]);
        $validator = new CuentaOrdenantePatron();

        $this->expectException(ValidatePagoException::class);
        $validator->validatePago($pago);
    }
}
