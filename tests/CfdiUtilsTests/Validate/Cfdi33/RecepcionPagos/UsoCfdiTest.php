<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos;

use CfdiUtils\Validate\Cfdi33\RecepcionPagos\UsoCfdi;
use CfdiUtils\Validate\Contracts\ValidatorInterface;
use CfdiUtils\Validate\Status;

final class UsoCfdiTest extends ValidateComplementoPagosTestCase
{
    /** @var UsoCfdi */
    protected ValidatorInterface $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new UsoCfdi();
    }

    public function testValidCase(): void
    {
        $comprobante = $this->getComprobante();
        $comprobante->addReceptor(['UsoCFDI' => 'P01']);

        $this->runValidate();

        $this->assertStatusEqualsCode(Status::ok(), 'PAGUSO01');
    }

    public function testInvalidUsoCfdi(): void
    {
        $comprobante = $this->getComprobante();
        $comprobante->addReceptor(['UsoCFDI' => 'P02']);

        $this->runValidate();

        $this->assertStatusEqualsCode(Status::error(), 'PAGUSO01');
    }

    public function testInvalidNoReceptor(): void
    {
        $this->runValidate();

        $this->assertStatusEqualsCode(Status::error(), 'PAGUSO01');
    }
}
