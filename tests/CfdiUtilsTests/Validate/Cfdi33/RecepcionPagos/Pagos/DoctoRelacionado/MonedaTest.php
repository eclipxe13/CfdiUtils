<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado;

use CfdiUtils\Elements\Pagos10\DoctoRelacionado;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado\Moneda;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado\ValidateDoctoException;
use PHPUnit\Framework\TestCase;

final class MonedaTest extends TestCase
{
    /**
     * @testWith ["MXN"]
     *           ["USD"]
     *           [""]
     *           [null]
     */
    public function testValid(?string $input): void
    {
        $docto = new DoctoRelacionado([
            'MonedaDR' => $input,
        ]);
        $validator = new Moneda();
        $validator->setIndex(0);

        $this->assertTrue($validator->validateDoctoRelacionado($docto));
    }

    /**
     * @testWith ["XXX"]
     */
    public function testInvalid(string $input): void
    {
        $docto = new DoctoRelacionado([
            'MonedaDR' => $input,
        ]);
        $validator = new Moneda();
        $validator->setIndex(0);

        $this->expectException(ValidateDoctoException::class);
        $validator->validateDoctoRelacionado($docto);
    }
}
