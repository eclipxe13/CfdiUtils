<?php
namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Elements\Pagos10\Pago;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\TipoCadenaPagoProhibido;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\ValidatePagoException;
use PHPUnit\Framework\TestCase;

class TipoCadenaPagoProhibidoTest extends TestCase
{
    /**
     * @param string $paymentForm
     * @param string|null $input
     * @testWith ["01", null]
     *           ["03", null]
     *           ["03", "SPEI"]
     */
    public function testValid(string $paymentForm, $input)
    {
        $pago = new Pago([
            'FormaDePagoP' => $paymentForm,
            'TipoCadPago' => $input,
        ]);
        $validator = new TipoCadenaPagoProhibido();

        $this->assertTrue($validator->validatePago($pago));
    }

    /**
     * @param string $paymentForm
     * @param string|null $input
     * @testWith ["01", "SPEI"]
     *           ["01", ""]
     */
    public function testInvalid(string $paymentForm, $input)
    {
        $pago = new Pago([
            'FormaDePagoP' => $paymentForm,
            'TipoCadPago' => $input,
        ]);
        $validator = new TipoCadenaPagoProhibido();

        $this->expectException(ValidatePagoException::class);
        $validator->validatePago($pago);
    }
}
