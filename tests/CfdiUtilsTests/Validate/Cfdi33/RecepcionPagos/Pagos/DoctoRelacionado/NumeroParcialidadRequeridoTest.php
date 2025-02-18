<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado;

use CfdiUtils\Elements\Pagos10\DoctoRelacionado;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado\NumeroParcialidadRequerido;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado\ValidateDoctoException;
use PHPUnit\Framework\TestCase;

final class NumeroParcialidadRequeridoTest extends TestCase
{
    public function testValid(): void
    {
        $docto = new DoctoRelacionado([
            'MetodoDePagoDR' => 'PPD',
            'NumParcialidad' => '1',
        ]);
        $validator = new NumeroParcialidadRequerido();
        $validator->setIndex(0);

        $this->assertTrue($validator->validateDoctoRelacionado($docto));
    }

    public function testInvalid(): void
    {
        $docto = new DoctoRelacionado([
            'MetodoDePagoDR' => 'PPD',
            'NumParcialidad' => null,
        ]);
        $validator = new NumeroParcialidadRequerido();
        $validator->setIndex(0);

        $this->expectException(ValidateDoctoException::class);
        $validator->validateDoctoRelacionado($docto);
    }
}
