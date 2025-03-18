<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Elements\Pagos10\Pago;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\BancoOrdenanteRfcProhibido;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\ValidatePagoException;
use PHPUnit\Framework\TestCase;

final class BancoOrdenanteRfcProhibidoTest extends TestCase
{
    /**
     * @testWith ["02", "COSC8001137NA"]
     *           ["02", ""]
     *           ["02", null]
     *           ["01", null]
     */
    public function testValid(string $paymentType, ?string $rfc): void
    {
        $pago = new Pago([
            'FormaDePagoP' => $paymentType,
            'RfcEmisorCtaOrd' => $rfc,
        ]);
        $validator = new BancoOrdenanteRfcProhibido();

        $this->assertTrue($validator->validatePago($pago));
    }

    /**
     * @testWith ["01", "COSC8001137NA"]
     *           ["01", ""]
     *           [null, "COSC8001137NA"]
     */
    public function testInvalid(?string $paymentType, string $rfc): void
    {
        $pago = new Pago([
            'FormaDePagoP' => $paymentType,
            'RfcEmisorCtaOrd' => $rfc,
        ]);
        $validator = new BancoOrdenanteRfcProhibido();

        $this->expectException(ValidatePagoException::class);
        $validator->validatePago($pago);
    }
}
