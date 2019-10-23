<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Elements\Pagos10\Pago;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\TipoCambioExists;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\ValidatePagoException;
use PHPUnit\Framework\TestCase;

class TipoCambioExistsTest extends TestCase
{
    /**
     * @param string|null $currency
     * @param string|null $exchangerate
     * @testWith ["MXN", null]
     *           ["USD", 18.5678]
     */
    public function testValidInput($currency, $exchangerate)
    {
        $pago = new Pago([
            'MonedaP' => $currency,
            'TipoCambioP' => $exchangerate,
        ]);
        $validator = new TipoCambioExists();
        $this->assertTrue($validator->validatePago($pago));
    }

    /**
     * @param string|null $currency
     * @param string|null $exchangerate
     * @testWith ["MXN", "1"]
     *           ["MXN", "1.23"]
     *           ["USD", null]
     *           ["USD", ""]
     */
    public function testInvalidInput($currency, $exchangerate)
    {
        $pago = new Pago([
            'MonedaP' => $currency,
            'TipoCambioP' => $exchangerate,
        ]);
        $validator = new TipoCambioExists();

        $this->expectException(ValidatePagoException::class);
        $validator->validatePago($pago);
    }
}
