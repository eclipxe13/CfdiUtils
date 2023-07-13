<?php

namespace CfdiUtilsTests\SumasPagos20;

use CfdiUtils\SumasPagos20\Decimal;
use CfdiUtilsTests\TestCase;

final class DecimalTest extends TestCase
{
    /** @return array<array{Decimal, Decimal, int}> */
    public function providerRound(): array
    {
        return [
            [new Decimal('45740.35'), new Decimal('45740.3490'), 2],
            [new Decimal('1.23'), new Decimal('1.234'), 2],
            [new Decimal('1.24'), new Decimal('1.235'), 2],
            [new Decimal('1.24'), new Decimal('1.236'), 2],
        ];
    }

    /** @dataProvider providerRound() */
    public function testRound(Decimal $expected, Decimal $value, int $decimals): void
    {
        $this->assertTrue(0 === $expected->compareTo($value->round($decimals)));
    }
}
