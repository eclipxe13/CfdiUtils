<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Elements\Pagos10\Pago;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\MonedaPago;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\ValidatePagoException;
use PHPUnit\Framework\TestCase;

class MonedaPagoTest extends TestCase
{
    public function testValid()
    {
        $pago = new Pago([
            'MonedaP' => '999',
        ]);
        $validator = new MonedaPago();
        $this->assertTrue($validator->validatePago($pago));
    }

    /**
     * @param string|null $currency
     * @testWith [null]
     *           [""]
     *           ["XXX"]
     */
    public function testInvalid($currency)
    {
        $pago = new Pago([
            'MonedaP' => $currency,
        ]);
        $validator = new MonedaPago();

        $this->expectException(ValidatePagoException::class);
        $validator->validatePago($pago);
    }
}
