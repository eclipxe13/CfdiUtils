<?php
namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado;

use CfdiUtils\Elements\Pagos10\DoctoRelacionado;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado\ImporteSaldoInsolutoRequerido;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado\ValidateDoctoException;
use PHPUnit\Framework\TestCase;

class ImporteSaldoInsolutoRequeridoTest extends TestCase
{
    public function testValid()
    {
        $docto = new DoctoRelacionado([
            'MetodoDePagoDR' => 'PPD',
            'ImpSaldoInsoluto' => '1',
        ]);
        $validator = new ImporteSaldoInsolutoRequerido();
        $validator->setIndex(0);

        $this->assertTrue($validator->validateDoctoRelacionado($docto));
    }

    public function testInvalid()
    {
        $docto = new DoctoRelacionado([
            'MetodoDePagoDR' => 'PPD',
            'ImpSaldoInsoluto' => null,
        ]);
        $validator = new ImporteSaldoInsolutoRequerido();
        $validator->setIndex(0);

        $this->expectException(ValidateDoctoException::class);
        $validator->validateDoctoRelacionado($docto);
    }
}
