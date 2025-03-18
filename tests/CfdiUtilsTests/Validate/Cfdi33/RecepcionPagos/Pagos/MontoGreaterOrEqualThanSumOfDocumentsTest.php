<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Elements\Pagos10\DoctoRelacionado;
use CfdiUtils\Elements\Pagos10\Pago;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\MontoGreaterOrEqualThanSumOfDocuments;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\ValidatePagoException;
use PHPUnit\Framework\TestCase;

final class MontoGreaterOrEqualThanSumOfDocumentsTest extends TestCase
{
    public function testValid(): void
    {
        $pago = new Pago([
            'MonedaP' => 'USD',
            'Monto' => '123.45',
        ]);
        $pago->multiDoctoRelacionado(...[
            ['ImpPagado' => '50.00'], // 50.00
            ['MonedaDR' => 'EUR', 'TipoCambioDR' => '0.5', 'ImpPagado' => '25.00'], // 25.00 / 0.5 => 50
            ['MonedaDR' => 'MXN', 'TipoCambioDR' => '18.7894', 'ImpPagado' => '440.61'], // 440.61 / 18.7894 => 23.45
        ]);

        $validator = new MontoGreaterOrEqualThanSumOfDocuments();

        $this->assertTrue($validator->validatePago($pago));
    }

    public function testInvalid(): void
    {
        $pago = new Pago([
            'MonedaP' => 'MXN',
            'Monto' => '123.45',
        ]);
        $pago->multiDoctoRelacionado(...[
            ['ImpPagado' => '50.00'], // 50.00
            ['MonedaDR' => 'EUR', 'TipoCambioDR' => '0.5', 'ImpPagado' => '25.01'], // 25.01 / 0.5 => 50.02
            ['MonedaDR' => 'MXN', 'TipoCambioDR' => '18.7894', 'ImpPagado' => '440.61'], // 440.61 / 18.7894 => 23.45
        ]);

        $validator = new MontoGreaterOrEqualThanSumOfDocuments();

        $this->expectException(ValidatePagoException::class);
        $validator->validatePago($pago);
    }

    public function testCalculateDocumentAmountWhenIsSet(): void
    {
        $validator = new MontoGreaterOrEqualThanSumOfDocuments();
        $amount = $validator->calculateDocumentAmount(new DoctoRelacionado([
            'ImpPagado' => '123.45',
        ]), new Pago());

        $this->assertEqualsWithDelta(123.45, $amount, 0.001);
    }

    public function testCalculateDocumentAmountWhenIsUndefined(): void
    {
        $pago = new Pago(['Monto' => '123.45']);
        $docto = $pago->addDoctoRelacionado();

        $validator = new MontoGreaterOrEqualThanSumOfDocuments();
        $amount = $validator->calculateDocumentAmount($docto, $pago);

        $this->assertEqualsWithDelta(123.45, $amount, 0.001);
    }

    public function testCalculateDocumentAmountWhenIsUndefinedWithExchangeRate(): void
    {
        $pago = new Pago(['Monto' => '123.45']);
        $docto = $pago->addDoctoRelacionado(['TipoCambioDR' => 'EUR']);

        $validator = new MontoGreaterOrEqualThanSumOfDocuments();
        $amount = $validator->calculateDocumentAmount($docto, $pago);

        $this->assertEqualsWithDelta(0, $amount, 0.001);
    }

    public function testCalculateDocumentAmountWhenIsUndefinedWithMoreDocuments(): void
    {
        $pago = new Pago(['Monto' => '123.45']);
        $pago->addDoctoRelacionado(); // first
        $docto = $pago->addDoctoRelacionado(); // second

        $validator = new MontoGreaterOrEqualThanSumOfDocuments();
        $amount = $validator->calculateDocumentAmount($docto, $pago);

        $this->assertEqualsWithDelta(0, $amount, 0.001);
    }
}
