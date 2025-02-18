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

    public function testValidCase(): void
    {
        $this->runValidate();

        foreach (range(1, 10) as $i) {
            $this->assertStatusEqualsCode(Status::ok(), sprintf('PAGCOMP%02d', $i));
        }
    }

    public function testErrorWithMoreThanOneComplementoPagos(): void
    {
        $this->getComprobante()->getComplemento()->add(new Pagos());
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'PAGCOMP01');
    }

    public function testErrorWithFormaPago(): void
    {
        $this->getComprobante()->addAttributes([
            'FormaPago' => '', // exists, even empty
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'PAGCOMP02');
    }

    public function testErrorWithCondicionesDePago(): void
    {
        $this->getComprobante()->addAttributes([
            'CondicionesDePago' => '', // exists, even empty
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'PAGCOMP03');
    }

    public function testErrorWithMetodoPago(): void
    {
        $this->getComprobante()->addAttributes([
            'MetodoPago' => '', // exists, even empty
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'PAGCOMP04');
    }

    /**
     * @testWith [""]
     *           [null]
     *           ["MXN"]
     */
    public function testErrorWithMonedaNotXxx(?string $input): void
    {
        $this->getComprobante()->addAttributes([
            'Moneda' => $input,
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'PAGCOMP05');
    }

    public function testErrorWithTipoCambio(): void
    {
        $this->getComprobante()->addAttributes([
            'TipoCambio' => '', // exists, even empty
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'PAGCOMP06');
    }

    public function testErrorWithDescuento(): void
    {
        $this->getComprobante()->addAttributes([
            'Descuento' => '', // exists, even empty
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'PAGCOMP07');
    }

    /**
     * @testWith [""]
     *           [null]
     *           ["0.0"]
     */
    public function testErrorWithSubTotalNotZero(?string $input): void
    {
        $this->getComprobante()->addAttributes([
            'SubTotal' => $input,
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'PAGCOMP08');
    }

    /**
     * @testWith [""]
     *           [null]
     *           ["0.0"]
     */
    public function testErrorWithTotalNotZero(?string $input): void
    {
        $this->getComprobante()->addAttributes([
            'Total' => $input,
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'PAGCOMP09');
    }

    public function testErrorWithImpuestos(): void
    {
        $this->getComprobante()->getImpuestos();
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'PAGCOMP10');
    }
}
