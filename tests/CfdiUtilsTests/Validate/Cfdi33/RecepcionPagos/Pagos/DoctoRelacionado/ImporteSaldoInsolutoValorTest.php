<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado;

use CfdiUtils\Elements\Pagos10\Pago;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado\ImporteSaldoInsolutoValor;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado\ValidateDoctoException;
use PHPUnit\Framework\TestCase;

class ImporteSaldoInsolutoValorTest extends TestCase
{
    /**
     * @param string $previous
     * @param string $payment
     * @param string $left
     * @testWith ["100.00", "100.00", "0.0"]
     */
    public function testValid($previous, $payment, $left)
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
     * @param string $previous
     * @param string $payment
     * @param string $left
     * @testWith ["150.00", "100.00", "50.0"]
     */
    public function testWithCalculate($previous, $payment, $left)
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
     * @param string $previous
     * @param string $payment
     * @param string $left
     * @testWith ["100.00", "100.00", "0.01"]
     *           ["100.00", "100.00", "-0.01"]
     *           ["100.01", "100.00", "0.00"]
     *           ["100.00", "100.01", "0.00"]
     */
    public function testInvalid($previous, $payment, $left)
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
