<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Elements\Pagos10\Pago;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\CuentaBeneficiariaProhibida;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\ValidatePagoException;
use PHPUnit\Framework\TestCase;

final class CuentaBeneficiariaProhibidaTest extends TestCase
{
    /**
     * @param string $paymentType
     * @param string|null $account
     * @testWith ["02", "x"]
     *           ["02", ""]
     *           ["02", null]
     *           ["01", null]
     */
    public function testValid(string $paymentType, ?string $account)
    {
        $pago = new Pago([
            'FormaDePagoP' => $paymentType,
            'CtaBeneficiario' => $account,
        ]);
        $validator = new CuentaBeneficiariaProhibida();

        $this->assertTrue($validator->validatePago($pago));
    }

    /**
     * @param string $paymentType
     * @param string $account
     * @testWith ["01", "x"]
     *           ["01", ""]
     */
    public function testInvalid(string $paymentType, string $account)
    {
        $pago = new Pago([
            'FormaDePagoP' => $paymentType,
            'CtaBeneficiario' => $account,
        ]);
        $validator = new CuentaBeneficiariaProhibida();

        $this->expectException(ValidatePagoException::class);
        $validator->validatePago($pago);
    }
}
