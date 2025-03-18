<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Elements\Pagos10\Pago;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\TipoCadenaPagoCadena;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\ValidatePagoException;
use PHPUnit\Framework\TestCase;

final class TipoCadenaPagoCadenaTest extends TestCase
{
    /**
     * @testWith [null, null]
     *           ["1", "1"]
     */
    public function testValid(?string $tipoCadPago, ?string $input): void
    {
        $pago = new Pago([
            'TipoCadPago' => $tipoCadPago,
            'CadPago' => $input,
        ]);
        $validator = new TipoCadenaPagoCadena();

        $this->assertTrue($validator->validatePago($pago));
    }

    /**
     * @testWith [null, "1"]
     *           ["", "1"]
     *           ["1", null]
     *           ["1", ""]
     *           [null, ""]
     *           ["", null]
     */
    public function testInvalid(?string $tipoCadPago, ?string $input): void
    {
        $pago = new Pago([
            'TipoCadPago' => $tipoCadPago,
            'CadPago' => $input,
        ]);
        $validator = new TipoCadenaPagoCadena();

        $this->expectException(ValidatePagoException::class);
        $validator->validatePago($pago);
    }
}
