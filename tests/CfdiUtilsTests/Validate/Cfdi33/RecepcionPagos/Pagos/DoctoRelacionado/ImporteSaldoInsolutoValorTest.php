<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado;

use CfdiUtils\Elements\Pagos10\Pago;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado\ImporteSaldoInsolutoValor;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado\ValidateDoctoException;
use PHPUnit\Framework\TestCase;

final class ImporteSaldoInsolutoValorTest extends TestCase
{
    /**
     * @testWith ["100.00", "100.00", "0.0"]
     */
    public function testValid(string $previous, string $payment, string $left): void
    {
        $pago = new Pago();
        $docto = $pago->addDoctoRelacionado([
            'ImpSaldoAnt' => $previous,
            'ImpPagado' => $payment,
            'ImpSaldoInsoluto' => $left,
        ]);
        $validator = new ImporteSaldoInsolutoValor();
        $validator->setIndex(0);
        $validator->setPago($pago);

        $this->assertTrue($validator->validateDoctoRelacionado($docto));
    }

    /**
     * @testWith ["150.00", "100.00", "50.0"]
     */
    public function testWithCalculate(string $previous, string $payment, string $left): void
    {
        $pago = new Pago(['Monto' => $payment]);
        $docto = $pago->addDoctoRelacionado([
            'ImpSaldoAnt' => $previous,
            'ImpSaldoInsoluto' => $left,
        ]);
        $validator = new ImporteSaldoInsolutoValor();
        $validator->setIndex(0);
        $validator->setPago($pago);

        $this->assertTrue($validator->validateDoctoRelacionado($docto));
    }

    /**
     * @testWith ["100.00", "100.00", "0.01"]
     *           ["100.00", "100.00", "-0.01"]
     *           ["100.01", "100.00", "0.00"]
     *           ["100.00", "100.01", "0.00"]
     */
    public function testInvalid(string $previous, string $payment, string $left): void
    {
        $pago = new Pago();
        $docto = $pago->addDoctoRelacionado([
            'ImpSaldoAnt' => $previous,
            'ImpPagado' => $payment,
            'ImpSaldoInsoluto' => $left,
        ]);
        $validator = new ImporteSaldoInsolutoValor();
        $validator->setIndex(0);
        $validator->setPago($pago);

        $this->expectException(ValidateDoctoException::class);
        $validator->validateDoctoRelacionado($docto);
    }
}
