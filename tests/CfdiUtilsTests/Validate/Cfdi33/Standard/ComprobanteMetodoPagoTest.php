<?php

namespace CfdiUtilsTests\Validate\Cfdi33\Standard;

use CfdiUtils\Validate\Cfdi33\Standard\ComprobanteMetodoPago;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\Validate\ValidateTestCase;

class ComprobanteMetodoPagoTest extends ValidateTestCase
{
    /** @var  ComprobanteMetodoPago */
    protected $validator;

    protected function setUp()
    {
        parent::setUp();
        $this->validator = new ComprobanteMetodoPago();
    }

    /**
     * @param string $tipoDeComprobante
     * @testWith ["T"]
     *           ["P"]
     */
    public function testValidCasesMetodoPagoExists($tipoDeComprobante)
    {
        $this->comprobante->addAttributes([
            'TipoDeComprobante' => $tipoDeComprobante,
            'MetodoPago' => null, // no MetodoPago
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::ok(), 'METPAG01');
    }

    /**
     * @param string $tipoDeComprobante
     * @testWith ["T"]
     *           ["P"]
     */
    public function testInvalidCasesMetodoPagoExists($tipoDeComprobante)
    {
        $this->comprobante->addAttributes([
            'TipoDeComprobante' => $tipoDeComprobante,
            'MetodoPago' => '',
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'METPAG01');
    }

    /**
     * @param string $metodoDePago
     * @testWith ["PUE"]
     *           ["PPD"]
     */
    public function testMetodoPagoHasValidValue(string $metodoDePago)
    {
        $this->comprobante->addAttributes([
            'MetodoPago' => $metodoDePago,
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::ok(), 'METPAG02');
    }

    /**
     * @param string $metodoDePago
     * @testWith [""]
     *           ["XXX"]
     */
    public function testMetodoPagoExistsWithInalidValue(string $metodoDePago)
    {
        $this->comprobante->addAttributes([
            'MetodoPago' => $metodoDePago,
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'METPAG02');
    }
}
