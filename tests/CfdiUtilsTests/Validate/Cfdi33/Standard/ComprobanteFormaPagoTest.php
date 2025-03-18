<?php

namespace CfdiUtilsTests\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\Node;
use CfdiUtils\Validate\Cfdi33\Standard\ComprobanteFormaPago;
use CfdiUtils\Validate\Contracts\ValidatorInterface;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\Validate\Validate33TestCase;

final class ComprobanteFormaPagoTest extends Validate33TestCase
{
    /** @var ComprobanteFormaPago */
    protected ValidatorInterface $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new ComprobanteFormaPago();
    }

    public function testValidateNothingWhenNotFormaPagoAndNotComplementoPago(): void
    {
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::none(), 'FORMAPAGO01');
    }

    public function testValidateNothingWhenFormaPagoAndNotComplementoPago(): void
    {
        $this->comprobante['FormaPago'] = '01'; // efectivo
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::none(), 'FORMAPAGO01');
    }

    public function testValidateOkWhenNotFormaPagoAndComplementoPago(): void
    {
        $this->comprobante
            ->addChild(new Node('cfdi:Complemento'))
            ->addChild(new Node('pago10:Pagos'));

        $this->runValidate();
        $this->assertStatusEqualsCode(Status::ok(), 'FORMAPAGO01');
    }

    public function testValidateErrorWhenFormaPagoAndComplementoPago(): void
    {
        $this->comprobante['FormaPago'] = '01'; // efectivo
        $this->comprobante
            ->addChild(new Node('cfdi:Complemento'))
            ->addChild(new Node('pago10:Pagos'));

        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'FORMAPAGO01');
    }
}
