<?php
namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Elements\Pagos10\Pago;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\MontoBetweenIntervalSumOfDocuments;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\ValidatePagoException;
use PHPUnit\Framework\TestCase;

class MontoBetweenIntervalSumOfDocumentsTest extends TestCase
{
    public function testValid()
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
     * @param string $monto
     * @testWith ["122.94"]
     *           ["123.97"]
     */
    public function testInvalids(string $monto)
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

    public function testValidWithSeveralDecimals()
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
}
