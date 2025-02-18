<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos\Helpers;

use CfdiUtils\Elements\Pagos10\DoctoRelacionado;
use CfdiUtils\Elements\Pagos10\Pago;
use PHPUnit\Framework\TestCase;

final class CalculateDocumentAmountTraitTest extends TestCase
{
    public function testCalculateDocumentAmountWhenIsSet(): void
    {
        $validator = new CalculateDocumentAmountUse();
        $amount = $validator->calculateDocumentAmount(new DoctoRelacionado([
            'ImpPagado' => '123.45',
        ]), new Pago());

        $this->assertEqualsWithDelta(123.45, $amount, 0.001);
    }

    public function testCalculateDocumentAmountWhenIsUndefined(): void
    {
        $pago = new Pago(['Monto' => '123.45']);
        $docto = $pago->addDoctoRelacionado();

        $validator = new CalculateDocumentAmountUse();
        $amount = $validator->calculateDocumentAmount($docto, $pago);

        $this->assertEqualsWithDelta(123.45, $amount, 0.001);
    }

    public function testCalculateDocumentAmountWhenIsUndefinedWithExchangeRate(): void
    {
        $pago = new Pago(['Monto' => '123.45']);
        $docto = $pago->addDoctoRelacionado(['TipoCambioDR' => 'EUR']);

        $validator = new CalculateDocumentAmountUse();
        $amount = $validator->calculateDocumentAmount($docto, $pago);

        $this->assertEqualsWithDelta(0, $amount, 0.001);
    }

    public function testCalculateDocumentAmountWhenIsUndefinedWithMoreDocuments(): void
    {
        $pago = new Pago(['Monto' => '123.45']);
        $pago->addDoctoRelacionado(); // first
        $docto = $pago->addDoctoRelacionado(); // second

        $validator = new CalculateDocumentAmountUse();
        $amount = $validator->calculateDocumentAmount($docto, $pago);

        $this->assertEqualsWithDelta(0, $amount, 0.001);
    }
}
