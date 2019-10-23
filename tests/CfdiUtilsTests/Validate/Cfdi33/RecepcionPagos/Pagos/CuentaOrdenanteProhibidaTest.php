<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Elements\Pagos10\Pago;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\CuentaOrdenanteProhibida;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\ValidatePagoException;
use PHPUnit\Framework\TestCase;

class CuentaOrdenanteProhibidaTest extends TestCase
{
    /**
     * @param string|null $paymentType
     * @param string|null $account
     * @testWith ["02", "x"]
     *           ["02", ""]
     *           ["02", null]
     *           ["01", null]
     */
    public function testValid($paymentType, $account)
    {
        $pago = new Pago([
            'FormaDePagoP' => $paymentType,
            'CtaOrdenante' => $account,
        ]);
        $validator = new CuentaOrdenanteProhibida();

        $this->assertTrue($validator->validatePago($pago));
    }

    /**
     * @param string|null $paymentType
     * @param string|null $account
     * @testWith ["01", "x"]
     *           ["01", ""]
     */
    public function testInvalid($paymentType, $account)
    {
        $pago = new Pago([
            'FormaDePagoP' => $paymentType,
            'CtaOrdenante' => $account,
        ]);
        $validator = new CuentaOrdenanteProhibida();

        $this->expectException(ValidatePagoException::class);
        $validator->validatePago($pago);
    }
}
