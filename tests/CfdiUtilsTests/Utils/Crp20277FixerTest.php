<?php

namespace CfdiUtilsTests\Utils;

use CfdiUtils\Elements\Pagos20\Pagos;
use CfdiUtils\Utils\Crp20277Fixer;
use CfdiUtilsTests\TestCase;

final class Crp20277FixerTest extends TestCase
{
    public const EXPECTED_FORMAT = '1.0000000000';

    public function testFixerChangesFormat(): void
    {
        $complemento = new Pagos();
        $equivalenciaDrUsd = '19.87654321';
        $equivalenciaDrMxn = '0.050310559';
        $firstPago = $complemento
            ->addPago(['MonedaP' => 'MXN'])
            ->multiDoctoRelacionado(
                ['MonedaDR' => 'USD', 'EquivalenciaDR' => $equivalenciaDrUsd],
                ['MonedaDR' => 'MXN', 'EquivalenciaDR' => '1'],
            );
        $secondPago = $complemento
            ->addPago(['MonedaP' => 'USD'])
            ->multiDoctoRelacionado(
                ['MonedaDR' => 'MXN', 'EquivalenciaDR' => $equivalenciaDrMxn],
                ['MonedaDR' => 'USD', 'EquivalenciaDR' => '1'],
            );
        $thirdPago = $complemento
            ->addPago(['MonedaP' => 'USD'])
            ->multiDoctoRelacionado(
                ['MonedaDR' => 'USD', 'EquivalenciaDR' => '1'],
            );

        Crp20277Fixer::staticFix($complemento);

        $this->assertSame(
            self::EXPECTED_FORMAT,
            $firstPago->children()->get(1)['EquivalenciaDR'],
            'DoctoRelacionados: 2, MonedaP: MXN, MonedaDR: MXN, expected to change'
        );
        $this->assertSame(
            $equivalenciaDrUsd,
            $firstPago->children()->get(0)['EquivalenciaDR'],
            'DoctoRelacionados: 2, MonedaP: MXN, MonedaDR: USD, expected not to change'
        );

        $this->assertSame(
            self::EXPECTED_FORMAT,
            $secondPago->children()->get(1)['EquivalenciaDR'],
            'DoctoRelacionados: 2, MonedaP: USD, MonedaDR: USD, expected to change'
        );
        $this->assertSame(
            $equivalenciaDrMxn,
            $secondPago->children()->get(0)['EquivalenciaDR'],
            'DoctoRelacionados: 2, MonedaP: USD, MonedaDR: MXN, expected not to change'
        );

        $this->assertSame(
            '1',
            $thirdPago->children()->get(0)['EquivalenciaDR'],
            'DoctoRelacionados: 1, MonedaP: USD, MonedaDR: USD, expected not to change'
        );
    }
}
