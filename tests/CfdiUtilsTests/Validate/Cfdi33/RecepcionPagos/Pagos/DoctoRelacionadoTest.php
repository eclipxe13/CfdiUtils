<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado;
use PHPUnit\Framework\TestCase;

final class DoctoRelacionadoTest extends TestCase
{
    public function testValidatorsCodes()
    {
        $expectedValidators = [];
        foreach (range(23, 33) as $i) {
            $expectedValidators[] = sprintf('PAGO%02d', $i);
        }

        $validator = new DoctoRelacionado();
        $validators = $validator->createValidators();

        $codes = [];
        foreach ($validators as $validator) {
            $codes[] = $validator->getCode();
        }

        $this->assertEquals($expectedValidators, $codes);
    }
}
