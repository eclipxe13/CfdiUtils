<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado;

use CfdiUtils\Elements\Pagos10\Pago;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado\TipoCambioValor;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado\ValidateDoctoException;
use PHPUnit\Framework\TestCase;

final class TipoCambioValorTest extends TestCase
{
    /**
     * @param string $currencyPayment
     * @param string $currencyDocument
     * @param string $exchangeRate
     * @testWith ["USD", "MXN", "1"]
     */
    public function testValid(string $currencyPayment, string $currencyDocument, string $exchangeRate)
    {
        $pago = new Pago([
            'MonedaP' => $currencyPayment,
        ]);
        $docto = $pago->addDoctoRelacionado([
            'MonedaDR' => $currencyDocument,
            'TipoCambioDR' => $exchangeRate,
        ]);
        $validator = new TipoCambioValor();
        $validator->setPago($pago);
        $validator->setIndex(0);

        $this->assertTrue($validator->validateDoctoRelacionado($docto));
    }

    /**
     * @param string $currencyPayment
     * @param string $currencyDocument
     * @param string|null $exchangeRate
     * @testWith ["USD", "MXN", "1.0"]
     *           ["USD", "MXN", ""]
     *           ["USD", "MXN", null]
     */
    public function testInvalid(string $currencyPayment, string $currencyDocument, ?string $exchangeRate)
    {
        $pago = new Pago([
            'MonedaP' => $currencyPayment,
        ]);
        $docto = $pago->addDoctoRelacionado([
            'MonedaDR' => $currencyDocument,
            'TipoCambioDR' => $exchangeRate,
        ]);
        $validator = new TipoCambioValor();
        $validator->setPago($pago);
        $validator->setIndex(0);

        $this->expectException(ValidateDoctoException::class);
        $validator->validateDoctoRelacionado($docto);
    }
}
