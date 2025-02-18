<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Elements\Pagos10\Pago;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\TipoCambioValue;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\ValidatePagoException;
use PHPUnit\Framework\TestCase;

final class TipoCambioValueTest extends TestCase
{
    /**
     * @param string|null $exchangerate
     * @testWith ["0.000002"]
     *           ["18.5623"]
     *           [null]
     */
    public function testValid(?string $exchangerate): void
    {
        $pago = new Pago([
            'TipoCambioP' => $exchangerate,
        ]);
        $validator = new TipoCambioValue();
        $this->assertTrue($validator->validatePago($pago));
    }

    /**
     * @param string $exchangerate
     * @testWith ["0.000001"]
     *           ["1.0000001"]
     *           ["-1"]
     *           ["not numeric"]
     *           [""]
     */
    public function testInvalid(string $exchangerate): void
    {
        $pago = new Pago([
            'TipoCambioP' => $exchangerate,
        ]);
        $validator = new TipoCambioValue();

        $this->expectException(ValidatePagoException::class);
        $validator->validatePago($pago);
    }
}
