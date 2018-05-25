<?php
namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Elements\Pagos10\Pago;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\BancoOrdenanteNombreRequerido;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\ValidatePagoException;
use PHPUnit\Framework\TestCase;

class BancoOrdenanteNombreRequeridoTest extends TestCase
{
    /**
     * @param string|null $rfc
     * @param string|null $name
     * @testWith ["XEXX010101000", "Foreign bank"]
     *           ["COSC8001137NA", "Banco X"]
     *           ["COSC8001137NA", null]
     *           [null, "Foreign bank"]
     *           [null, null]
     */
    public function testValid($rfc, $name)
    {
        $pago = new Pago([
            'RfcEmisorCtaOrd' => $rfc,
            'NomBancoOrdExt' => $name,
        ]);
        $validator = new BancoOrdenanteNombreRequerido();

        $this->assertTrue($validator->validatePago($pago));
    }

    /**
     * @param string|null $rfc
     * @param string|null $name
     * @testWith ["XEXX010101000", ""]
     *           ["XEXX010101000", null]
     */
    public function testInvalid($rfc, $name)
    {
        $pago = new Pago([
            'RfcEmisorCtaOrd' => $rfc,
            'NomBancoOrdExt' => $name,
        ]);
        $validator = new BancoOrdenanteNombreRequerido();

        $this->expectException(ValidatePagoException::class);
        $validator->validatePago($pago);
    }
}
