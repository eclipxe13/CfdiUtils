<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Elements\Pagos10\Pago;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\MontoDecimals;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\ValidatePagoException;
use PHPUnit\Framework\TestCase;

final class MontoDecimalsTest extends TestCase
{
    public function testValid()
    {
        $pago = new Pago([
            'MonedaP' => 'USD', // 2 decimals
            'Monto' => '1.00',
        ]);
        $validator = new MontoDecimals();

        $this->assertTrue($validator->validatePago($pago));
    }

    /**
     * @param string $amount
     * @testWith ["0.001"]
     *           ["0.000"]
     *           ["0.123"]
     */
    public function testInvalid(string $amount)
    {
        $pago = new Pago([
            'MonedaP' => 'USD', // 2 decimals
            'Monto' => $amount,
        ]);
        $validator = new MontoDecimals();

        $this->expectException(ValidatePagoException::class);
        $validator->validatePago($pago);
    }
}
