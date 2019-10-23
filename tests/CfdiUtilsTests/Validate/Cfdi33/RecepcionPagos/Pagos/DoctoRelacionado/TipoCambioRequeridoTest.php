<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado;

use CfdiUtils\Elements\Pagos10\Pago;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado\TipoCambioRequerido;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado\ValidateDoctoException;
use PHPUnit\Framework\TestCase;

class TipoCambioRequeridoTest extends TestCase
{
    /**
     * @param string $currencyPayment
     * @param string $currencyDocument
     * @param string|null $exchangeRate
     * @testWith ["USD", "USD", null]
     *           ["MXN", "USD", "19.9876"]
     */
    public function testValid($currencyPayment, $currencyDocument, $exchangeRate)
    {
        $pago = new Pago([
            'MonedaP' => $currencyPayment,
        ]);
        $docto = $pago->addDoctoRelacionado([
            'MonedaDR' => $currencyDocument,
            'TipoCambioDR' => $exchangeRate,
        ]);
        $validator = new TipoCambioRequerido();
        $validator->setPago($pago);
        $validator->setIndex(0);

        $this->assertTrue($validator->validateDoctoRelacionado($docto));
    }

    /**
     * @param string $currencyPayment
     * @param string $currencyDocument
     * @param string|null $exchangeRate
     * @testWith ["USD", "USD", "19.9876"]
     *           ["MXN", "USD", null]
     */
    public function testInvalid($currencyPayment, $currencyDocument, $exchangeRate)
    {
        $pago = new Pago([
            'MonedaP' => $currencyPayment,
        ]);
        $docto = $pago->addDoctoRelacionado([
            'MonedaDR' => $currencyDocument,
            'TipoCambioDR' => $exchangeRate,
        ]);
        $validator = new TipoCambioRequerido();
        $validator->setPago($pago);
        $validator->setIndex(0);

        $this->expectException(ValidateDoctoException::class);
        $validator->validateDoctoRelacionado($docto);
    }
}
