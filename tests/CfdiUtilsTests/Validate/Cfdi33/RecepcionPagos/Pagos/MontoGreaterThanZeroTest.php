<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Elements\Pagos10\Pago;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\MontoGreaterThanZero;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\ValidatePagoException;
use PHPUnit\Framework\TestCase;

final class MontoGreaterThanZeroTest extends TestCase
{
    /**
     * @param string $amount
     * @testWith ["0.000001"]
     *           ["1"]
     */
    public function testValid(string $amount)
    {
        $pago = new Pago([
            'Monto' => $amount,
        ]);
        $validator = new MontoGreaterThanZero();
        $this->assertTrue($validator->validatePago($pago));
    }

    /**
     * @param string|null $amount
     * @testWith ["0.0000001"]
     *           ["0"]
     *           ["-1"]
     *           [null]
     *           [""]
     *           ["not numeric"]
     */
    public function testPagoMontoGreaterThanZeroInvalid(?string $amount)
    {
        $pago = new Pago([
            'Monto' => $amount,
        ]);
        $validator = new MontoGreaterThanZero();

        $this->expectException(ValidatePagoException::class);
        $validator->validatePago($pago);
    }
}
