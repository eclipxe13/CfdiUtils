<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos\Helpers;

use CfdiUtils\Elements\Pagos10\DoctoRelacionado;
use CfdiUtils\Elements\Pagos10\Pago;
use PHPUnit\Framework\TestCase;

class CalculateDocumentAmountTraitTest extends TestCase
{
    public function testCalculateDocumentAmountWhenIsSet()
    {
        $validator = new CalculateDocumentAmountUse();
        $amount = $validator->calculateDocumentAmount(new DoctoRelacionado([
            'ImpPagado' => '123.45',
        ]), new Pago());

        $this->assertEquals(123.45, $amount, '', 0.001);
    }

    public function testCalculateDocumentAmountWhenIsUndefined()
    {
        $pago = new Pago(['Monto' => '123.45']);
        $docto = $pago->addDoctoRelacionado();

        $validator = new CalculateDocumentAmountUse();
        $amount = $validator->calculateDocumentAmount($docto, $pago);

        $this->assertEquals(123.45, $amount, '', 0.001);
    }

    public function testCalculateDocumentAmountWhenIsUndefinedWithExchangeRate()
    {
        $pago = new Pago(['Monto' => '123.45']);
        $docto = $pago->addDoctoRelacionado(['TipoCambioDR' => 'EUR']);

        $validator = new CalculateDocumentAmountUse();
        $amount = $validator->calculateDocumentAmount($docto, $pago);

        $this->assertEquals(0, $amount, '', 0.001);
    }

    public function testCalculateDocumentAmountWhenIsUndefinedWithMoreDocuments()
    {
        $pago = new Pago(['Monto' => '123.45']);
        $pago->addDoctoRelacionado(); // first
        $docto = $pago->addDoctoRelacionado(); // second

        $validator = new CalculateDocumentAmountUse();
        $amount = $validator->calculateDocumentAmount($docto, $pago);

        $this->assertEquals(0, $amount, '', 0.001);
    }
}
