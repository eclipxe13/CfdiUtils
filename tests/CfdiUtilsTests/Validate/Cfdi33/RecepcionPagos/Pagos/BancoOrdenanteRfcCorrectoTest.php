<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Elements\Pagos10\Pago;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\BancoOrdenanteRfcCorrecto;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\ValidatePagoException;
use PHPUnit\Framework\TestCase;

class BancoOrdenanteRfcCorrectoTest extends TestCase
{
    /**
     * @param string|null $rfc
     * @testWith ["COSC8001137NA"]
     *           ["XEXX010101000"]
     *           [null]
     */
    public function testValid(?string $rfc)
    {
        $pago = new Pago([
            'RfcEmisorCtaOrd' => $rfc,
        ]);
        $validator = new BancoOrdenanteRfcCorrecto();

        $this->assertTrue($validator->validatePago($pago));
    }

    /**
     * @param string $rfc
     * @testWith ["COSC8099137N1"]
     *           ["XAXX010101000"]
     *           [""]
     */
    public function testInvalid(string $rfc)
    {
        $pago = new Pago([
            'RfcEmisorCtaOrd' => $rfc,
        ]);
        $validator = new BancoOrdenanteRfcCorrecto();

        $this->expectException(ValidatePagoException::class);
        $validator->validatePago($pago);
    }
}
