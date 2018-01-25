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

    public function providerValidCases()
    {
        return[
            ['T', null, 'METPAG01'],
            ['P', null, 'METPAG01'],
            ['N', null, 'METPAG01'],
            ['I', 'PUE', 'METPAG02'],
            ['I', 'PPD', 'METPAG02'],
            ['E', 'PUE', 'METPAG02'],
            ['I', 'PPD', 'METPAG02'],
        ];
    }

    /**
     * @param string $tipoDeComprobante
     * @param mixed $metodoDePago
     * @param string $ok
     * @dataProvider providerValidCases
     */
    public function testValidCases($tipoDeComprobante, $metodoDePago, $ok)
    {
        $this->comprobante->addAttributes([
            'TipoDeComprobante' => $tipoDeComprobante,
            'MetodoPago' => $metodoDePago,
        ]);
        $this->runValidate();
        $this->assertFalse($this->asserts->hasErrors());
        $this->assertStatusEqualsCode(Status::ok(), $ok);
    }

    public function providerInvalidCases()
    {
        return[
            ['T', 'PUE', 'METPAG01'],
            ['T', '', 'METPAG01'],
            ['P', 'PUE', 'METPAG01'],
            ['P', '', 'METPAG01'],
            ['N', 'PUE', 'METPAG01'],
            ['N', '', 'METPAG01'],
            ['I', null, 'METPAG02'],
            ['I', null, 'METPAG02'],
            ['E', 'XXX', 'METPAG02'],
            ['I', 'XXX', 'METPAG02'],
        ];
    }

    /**
     * @param string $tipoDeComprobante
     * @param mixed $metodoDePago
     * @param string $error
     * @dataProvider providerInvalidCases
     */
    public function testInvalidCases($tipoDeComprobante, $metodoDePago, $error)
    {
        $this->comprobante->addAttributes([
            'TipoDeComprobante' => $tipoDeComprobante,
            'MetodoPago' => $metodoDePago,
        ]);
        $this->runValidate();
        $this->assertTrue($this->asserts->hasErrors());
        $this->assertStatusEqualsCode(Status::error(), $error);
    }

    public function providerNoneCases()
    {
        return [
            [null, ''],
            ['', ''],
            ['X', ''],
        ];
    }
    /**
     * @param mixed $tipoDeComprobante
     * @param string $metodoDePago
     * @dataProvider providerNoneCases
     */
    public function testNoneCases($tipoDeComprobante, $metodoDePago)
    {
        $this->comprobante->addAttributes([
            'TipoDeComprobante' => $tipoDeComprobante,
            'MetodoPago' => $metodoDePago,
        ]);
        $this->runValidate();
        $this->assertFalse($this->asserts->hasErrors());
        $this->assertCount(2, $this->asserts->byStatus(Status::none()));
    }
}
