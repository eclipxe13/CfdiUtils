<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos;

use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos;
use CfdiUtils\Validate\Contracts\ValidatorInterface;
use CfdiUtils\Validate\Status;

final class PagosTest extends ValidateComplementoPagosTestCase
{
    /** @var Pagos */
    protected ValidatorInterface $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new Pagos();
    }

    public function testValidCase(): void
    {
        $this->complemento->addPago();
        $this->runValidate();

        $this->assertStatusEqualsCode(Status::ok(), 'PAGOS01');
    }

    public function testWithoutNodes(): void
    {
        $this->runValidate();

        $this->assertStatusEqualsCode(Status::error(), 'PAGOS01');
    }
}
