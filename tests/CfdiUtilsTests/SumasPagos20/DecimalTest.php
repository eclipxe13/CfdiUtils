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
            '1.234 -> 1.23' => [new Decimal('1.23'), new Decimal('1.234'), 2],
            '1.235 -> 1.24' => [new Decimal('1.24'), new Decimal('1.235'), 2],
            '1.236 -> 1.24' => [new Decimal('1.24'), new Decimal('1.236'), 2],
            '1.237 -> 1.24' => [new Decimal('1.24'), new Decimal('1.237'), 2],
            '1.238 -> 1.24' => [new Decimal('1.24'), new Decimal('1.238'), 2],
            '1.239 -> 1.24' => [new Decimal('1.24'), new Decimal('1.239'), 2],
            '1.240 -> 1.24' => [new Decimal('1.24'), new Decimal('1.240'), 2],
            '1.241 -> 1.24' => [new Decimal('1.24'), new Decimal('1.241'), 2],
            '1.242 -> 1.24' => [new Decimal('1.24'), new Decimal('1.242'), 2],
            '1.243 -> 1.24' => [new Decimal('1.24'), new Decimal('1.243'), 2],
            '1.244 -> 1.24' => [new Decimal('1.24'), new Decimal('1.244'), 2],
            '1.245 -> 1.25' => [new Decimal('1.25'), new Decimal('1.245'), 2],
            '45740.3490 -> 45740.35' => [new Decimal('45740.35'), new Decimal('45740.3490'), 2],
        ];
    }

    /** @dataProvider providerRound() */
    public function testRound(Decimal $expected, Decimal $value, int $decimals): void
    {
        $this->assertSame(0, $expected->compareTo($value->round($decimals)));
    }
}
