<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado;

use CfdiUtils\Elements\Pagos10\DoctoRelacionado;
use CfdiUtils\Elements\Pagos10\Pago;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado\ImportePagadoValor;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado\ValidateDoctoException;
use PHPUnit\Framework\TestCase;

final class ImportePagadoValorTest extends TestCase
{
    /**
     * @param string $input
     * @testWith ["0.01"]
     *           ["123456.78"]
     */
    public function testValid(string $input)
    {
        $docto = new DoctoRelacionado([
            'ImpPagado' => $input,
        ]);
        $validator = new ImportePagadoValor();
        $validator->setIndex(0);

        $this->assertTrue($validator->validateDoctoRelacionado($docto));
    }

    public function testWithCalculate()
    {
        $pago = new Pago(['Monto' => 123]);
        $docto = $pago->addDoctoRelacionado();
        $validator = new ImportePagadoValor();
        $validator->setIndex(0);
        $validator->setPago($pago);

        $this->assertTrue($validator->validateDoctoRelacionado($docto));
    }

    /**
     * @param string|null $input
     * @testWith ["0"]
     *           ["-123.45"]
     *           [""]
     *           [null]
     */
    public function testInvalid(?string $input)
    {
        $pago = new Pago();
        $docto = $pago->addDoctoRelacionado([
            'ImpPagado' => $input,
        ]);
        $validator = new ImportePagadoValor();
        $validator->setIndex(0);
        $validator->setPago($pago);

        $this->expectException(ValidateDoctoException::class);
        $validator->validateDoctoRelacionado($docto);
    }
}
