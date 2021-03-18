<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos;

use CfdiUtils\Elements\Pagos10\Pagos;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\ComprobantePagos;
use CfdiUtils\Validate\Status;

final class ComprobantePagosTest extends ValidateComplementoPagosTestCase
{
    /** @var ComprobantePagos */
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new ComprobantePagos();

        // setup a valid case and in the test change to make it fail
        $comprobante = $this->getComprobante();
        $comprobante->addAttributes([
            'Moneda' => 'XXX',
            'SubTotal' => '0',
            'Total' => '0',
        ]);
    }

    public function testValidCase()
    {
        $this->runValidate();

        foreach (range(1, 10) as $i) {
            $this->assertStatusEqualsCode(Status::ok(), sprintf('PAGCOMP%02d', $i));
        }
    }

    public function testErrorWithMoreThanOneComplementoPagos()
    {
        $this->getComprobante()->getComplemento()->add(new Pagos());
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'PAGCOMP01');
    }

    public function testErrorWithFormaPago()
    {
        $this->getComprobante()->addAttributes([
            'FormaPago' => '', // exists, even empty
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'PAGCOMP02');
    }

    public function testErrorWithCondicionesDePago()
    {
        $this->getComprobante()->addAttributes([
            'CondicionesDePago' => '', // exists, even empty
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'PAGCOMP03');
    }

    public function testErrorWithMetodoPago()
    {
        $this->getComprobante()->addAttributes([
            'MetodoPago' => '', // exists, even empty
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'PAGCOMP04');
    }

    /**
     * @param string|null $input
     * @testWith [""]
     *           [null]
     *           ["MXN"]
     */
    public function testErrorWithMonedaNotXxx(?string $input)
    {
        $this->getComprobante()->addAttributes([
            'Moneda' => $input,
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'PAGCOMP05');
    }

    public function testErrorWithTipoCambio()
    {
        $this->getComprobante()->addAttributes([
            'TipoCambio' => '', // exists, even empty
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'PAGCOMP06');
    }

    public function testErrorWithDescuento()
    {
        $this->getComprobante()->addAttributes([
            'Descuento' => '', // exists, even empty
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'PAGCOMP07');
    }

    /**
     * @param string|null $input
     * @testWith [""]
     *           [null]
     *           ["0.0"]
     */
    public function testErrorWithSubTotalNotZero(?string $input)
    {
        $this->getComprobante()->addAttributes([
            'SubTotal' => $input,
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'PAGCOMP08');
    }

    /**
     * @param string|null $input
     * @testWith [""]
     *           [null]
     *           ["0.0"]
     */
    public function testErrorWithTotalNotZero(?string $input)
    {
        $this->getComprobante()->addAttributes([
            'Total' => $input,
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'PAGCOMP09');
    }

    public function testErrorWithImpuestos()
    {
        $this->getComprobante()->getImpuestos();
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'PAGCOMP10');
    }
}
