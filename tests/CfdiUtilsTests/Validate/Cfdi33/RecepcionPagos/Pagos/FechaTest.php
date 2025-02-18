<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Elements\Pagos10\Pago;
use CfdiUtils\Utils\Format;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\Fecha;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\ValidatePagoException;
use CfdiUtilsTests\Validate\Validate33TestCase;

final class FechaTest extends Validate33TestCase
{
    public function testValid(): void
    {
        $pagoNode = new Pago([
            'FechaPago' => Format::datetime(time()),
        ]);

        $validator = new Fecha();

        $this->assertTrue($validator->validatePago($pagoNode));
    }

    /**
     * @testWith [null]
     *           [""]
     *           ["not a date"]
     *           ["2018-01-01"]
     */
    public function testInvalid(?string $fechaPago): void
    {
        $pagoNode = new Pago([
            'FechaPago' => $fechaPago,
        ]);

        $validator = new Fecha();

        $this->expectException(ValidatePagoException::class);

        $validator->validatePago($pagoNode);
    }
}
