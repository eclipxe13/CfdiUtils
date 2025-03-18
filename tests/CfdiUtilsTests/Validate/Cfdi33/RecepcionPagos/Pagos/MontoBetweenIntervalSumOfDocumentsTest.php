<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Elements\Pagos10\Pago;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\MontoBetweenIntervalSumOfDocuments;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\ValidatePagoException;
use PHPUnit\Framework\TestCase;

final class MontoBetweenIntervalSumOfDocumentsTest extends TestCase
{
    public function testValid(): void
    {
        $pago = new Pago([
            'MonedaP' => 'USD',
            'Monto' => '123.45',
        ]);
        $pago->multiDoctoRelacionado(...[
            ['ImpPagado' => '50.00'], // 50.00
            ['MonedaDR' => 'EUR', 'TipoCambioDR' => '0.50', 'ImpPagado' => '25.00'], // 25.00 / 0.50 => 50
            ['MonedaDR' => 'MXN', 'TipoCambioDR' => '18.7894', 'ImpPagado' => '440.61'], // 440.61 / 18.7894 => 23.45
        ]);

        $validator = new MontoBetweenIntervalSumOfDocuments();
        $this->assertTrue($validator->validatePago($pago));
    }

    /**
     * This is testing lower bound (122.94) and upper bound (123.97)
     * @testWith ["122.93"]
     *           ["123.98"]
     */
    public function testInvalids(string $monto): void
    {
        $pago = new Pago([
            'MonedaP' => 'USD',
            'Monto' => $monto,
        ]);
        $pago->multiDoctoRelacionado(...[
            ['ImpPagado' => '20.00'], // 20.00
            ['MonedaDR' => 'USD', 'ImpPagado' => '30.00'], // 30.00
            ['MonedaDR' => 'EUR', 'TipoCambioDR' => '0.50', 'ImpPagado' => '25.00'], // 25.00 / 0.50 => 50
            ['MonedaDR' => 'MXN', 'TipoCambioDR' => '18.7894', 'ImpPagado' => '440.61'], // 440.61 / 18.7894 => 23.45
        ]);

        $validator = new MontoBetweenIntervalSumOfDocuments();

        $this->expectException(ValidatePagoException::class);
        $validator->validatePago($pago);
    }

    public function testValidWithSeveralDecimals(): void
    {
        // payment was made of 5,137.42 USD (ER: 18.7694) => 96,426.29 MXN
        // to pay a document on USD
        $pago = new Pago([
            'MonedaP' => 'MXN',
            'Monto' => '96426.29',
        ]);
        $pago->addDoctoRelacionado([
            'MonedaDR' => 'USD',
            'TipoCambioDR' => number_format(1 / 18.7694, 4),
            'ImpPagado' => '5137.42',
        ]);

        $validator = new MontoBetweenIntervalSumOfDocuments();
        $this->assertTrue($validator->validatePago($pago));
    }

    public function testValidWithMultiDocuments(): void
    {
        $pago = new Pago([
            'MonedaP' => 'MXN',
            'Monto' => '21588.07',
        ]);
        $pago->multiDoctoRelacionado(...[
            ['MonedaDR' => 'MXN', 'ImpPagado' => '6826.60'],
            ['MonedaDR' => 'MXN', 'ImpPagado' => '2114.52'],
            ['MonedaDR' => 'MXN', 'ImpPagado' => '11245.04'],
            ['MonedaDR' => 'MXN', 'ImpPagado' => '1401.91'],
        ]);
        $validator = new MontoBetweenIntervalSumOfDocuments();
        $this->assertTrue($validator->validatePago($pago));
    }

    public function providerValidWithRandomAmounts(): array
    {
        $randomValues = [];
        for ($i = 0; $i < 20; $i++) {
            $randomValues[] = [random_int(1, 99999999) / 100];
        }
        return $randomValues;
    }

    /**
     * @dataProvider providerValidWithRandomAmounts
     */
    public function testValidWithRandomAmounts(float $amount): void
    {
        $pago = new Pago([
            'MonedaP' => 'MXN',
            'Monto' => number_format($amount, 2, '.', ''),
        ]);
        $pago->multiDoctoRelacionado(...[
            ['MonedaDR' => 'MXN', 'ImpPagado' => number_format($amount / 3, 2, '.', '')],
            ['MonedaDR' => 'MXN', 'ImpPagado' => number_format(2 * $amount / 3, 2, '.', '')],
        ]);
        $validator = new MontoBetweenIntervalSumOfDocuments();
        $this->assertTrue($validator->validatePago($pago));
    }
}
