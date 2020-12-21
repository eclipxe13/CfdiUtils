<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado;

use CfdiUtils\Elements\Pagos10\DoctoRelacionado;
use CfdiUtils\Elements\Pagos10\Pago;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado\ImportePagadoRequerido;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado\ValidateDoctoException;
use PHPUnit\Framework\TestCase;

class ImportePagadoRequeridoTest extends TestCase
{
    public function testValid()
    {
        $docto = new DoctoRelacionado([
            'ImpPagado' => '1',
        ]);
        $validator = new ImportePagadoRequerido();
        $validator->setIndex(0);

        $this->assertTrue($validator->validateDoctoRelacionado($docto));
    }

    /**
     * @param string $exchangeRate
     * @testWith ["19.8765"]
     *           [""]
     */
    public function testInvalidExchangeRate(string $exchangeRate)
    {
        $pago = new Pago();
        $docto = $pago->addDoctoRelacionado([
            'TipoCambioDR' => $exchangeRate, // exists!
        ]);
        $validator = new ImportePagadoRequerido();
        $validator->setIndex(0);
        $validator->setPago($pago);

        $this->expectException(ValidateDoctoException::class);
        $this->expectExceptionMessage('existe el tipo de cambio');
        $validator->validateDoctoRelacionado($docto);
    }

    public function testInvalidMoreThanOneDocument()
    {
        $pago = new Pago();
        $docto = $pago->addDoctoRelacionado();
        $pago->addDoctoRelacionado(); // second document

        $validator = new ImportePagadoRequerido();
        $validator->setIndex(0);
        $validator->setPago($pago);

        $this->expectException(ValidateDoctoException::class);
        $this->expectExceptionMessage('hay más de 1 documento en el pago');
        $validator->validateDoctoRelacionado($docto);
    }
}
