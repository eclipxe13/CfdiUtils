<?php

namespace CfdiUtilsTests\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\Node;
use CfdiUtils\Validate\Cfdi33\Standard\ComprobanteFormaPago;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\Validate\ValidateTestCase;

final class ComprobanteFormaPagoTest extends ValidateTestCase
{
    /** @var ComprobanteFormaPago */
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new ComprobanteFormaPago();
    }

    public function testValidateNothingWhenNotFormaPagoAndNotComplementoPago()
    {
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::none(), 'FORMAPAGO01');
    }

    public function testValidateNothingWhenFormaPagoAndNotComplementoPago()
    {
        $this->comprobante['FormaPago'] = '01'; // efectivo
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::none(), 'FORMAPAGO01');
    }

    public function testValidateOkWhenNotFormaPagoAndComplementoPago()
    {
        $this->comprobante
            ->addChild(new Node('cfdi:Complemento'))
            ->addChild(new Node('pago10:Pagos'));

        $this->runValidate();
        $this->assertStatusEqualsCode(Status::ok(), 'FORMAPAGO01');
    }

    public function testValidateErrorWhenFormaPagoAndComplementoPago()
    {
        $this->comprobante['FormaPago'] = '01'; // efectivo
        $this->comprobante
            ->addChild(new Node('cfdi:Complemento'))
            ->addChild(new Node('pago10:Pagos'));

        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'FORMAPAGO01');
    }
}
