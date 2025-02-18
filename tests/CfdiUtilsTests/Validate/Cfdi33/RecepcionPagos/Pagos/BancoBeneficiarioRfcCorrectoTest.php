<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Elements\Pagos10\Pago;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\BancoBeneficiarioRfcCorrecto;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\ValidatePagoException;
use PHPUnit\Framework\TestCase;

final class BancoBeneficiarioRfcCorrectoTest extends TestCase
{
    /**
     * @param string|null $rfc
     * @testWith ["COSC8001137NA"]
     *           ["XEXX010101000"]
     *           [null]
     */
    public function testValid(?string $rfc): void
    {
        $pago = new Pago([
            'RfcEmisorCtaBen' => $rfc,
        ]);
        $validator = new BancoBeneficiarioRfcCorrecto();

        $this->assertTrue($validator->validatePago($pago));
    }

    /**
     * @param string|null $rfc
     * @testWith ["COSC8099137N1"]
     *           ["XAXX010101000"]
     *           [""]
     */
    public function testInvalid(?string $rfc): void
    {
        $pago = new Pago([
            'RfcEmisorCtaBen' => $rfc,
        ]);
        $validator = new BancoBeneficiarioRfcCorrecto();

        $this->expectException(ValidatePagoException::class);
        $validator->validatePago($pago);
    }
}
