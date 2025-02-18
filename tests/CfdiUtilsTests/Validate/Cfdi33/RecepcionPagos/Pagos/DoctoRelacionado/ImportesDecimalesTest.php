<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado;

use CfdiUtils\Elements\Pagos10\Pago;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado\ImportesDecimales;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado\ValidateDoctoException;
use PHPUnit\Framework\TestCase;

final class ImportesDecimalesTest extends TestCase
{
    /**
     * @param string $currency
     * @param string $previous
     * @param string $payment
     * @param string $left
     * @testWith ["MXN", "100.00", "100.00", "0.00"]
     *           ["MXN", "100.0", "100.0", "0.0"]
     *           ["MXN", "100", "100", "0"]
     */
    public function testValid(string $currency, string $previous, string $payment, string $left): void
    {
        $pago = new Pago();
        $docto = $pago->addDoctoRelacionado([
            'MonedaDR' => $currency,
            'ImpSaldoAnt' => $previous,
            'ImpPagado' => $payment,
            'ImpSaldoInsoluto' => $left,
        ]);
        $validator = new ImportesDecimales();
        $validator->setIndex(0);
        $validator->setPago($pago);

        $this->assertTrue($validator->validateDoctoRelacionado($docto));
    }

    /**
     * @param string $currency
     * @param string $previous
     * @param string $payment
     * @param string $left
     * @testWith ["MXN", "100.000", "100.00", "0.00"]
     *           ["MXN", "100.00", "100.000", "0.00"]
     *           ["MXN", "100.00", "100.00", "0.000"]
     */
    public function testInvalid(string $currency, string $previous, string $payment, string $left): void
    {
        $pago = new Pago();
        $docto = $pago->addDoctoRelacionado([
            'MonedaDR' => $currency,
            'ImpSaldoAnt' => $previous,
            'ImpPagado' => $payment,
            'ImpSaldoInsoluto' => $left,
        ]);
        $validator = new ImportesDecimales();
        $validator->setIndex(0);
        $validator->setPago($pago);

        $this->expectException(ValidateDoctoException::class);
        $validator->validateDoctoRelacionado($docto);
    }
}
