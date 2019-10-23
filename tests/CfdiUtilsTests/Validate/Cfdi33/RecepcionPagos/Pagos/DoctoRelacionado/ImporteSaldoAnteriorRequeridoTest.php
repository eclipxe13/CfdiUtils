<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado;

use CfdiUtils\Elements\Pagos10\DoctoRelacionado;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado\ImporteSaldoAnteriorRequerido;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado\ValidateDoctoException;
use PHPUnit\Framework\TestCase;

class ImporteSaldoAnteriorRequeridoTest extends TestCase
{
    public function testValid()
    {
        $docto = new DoctoRelacionado([
            'MetodoDePagoDR' => 'PPD',
            'ImpSaldoAnt' => '1',
        ]);
        $validator = new ImporteSaldoAnteriorRequerido();
        $validator->setIndex(0);

        $this->assertTrue($validator->validateDoctoRelacionado($docto));
    }

    public function testInvalid()
    {
        $docto = new DoctoRelacionado([
            'MetodoDePagoDR' => 'PPD',
            'ImpSaldoAnt' => null,
        ]);
        $validator = new ImporteSaldoAnteriorRequerido();
        $validator->setIndex(0);

        $this->expectException(ValidateDoctoException::class);
        $validator->validateDoctoRelacionado($docto);
    }
}
