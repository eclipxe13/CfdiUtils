<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos;

use CfdiUtils\Validate\Cfdi33\RecepcionPagos\UsoCfdi;
use CfdiUtils\Validate\Status;

class UsoCfdiTest extends ValidateComplementoPagosTestCase
{
    /** @var UsoCfdi */
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new UsoCfdi();
    }

    public function testValidCase()
    {
        $comprobante = $this->getComprobante();
        $comprobante->addReceptor(['UsoCFDI' => 'P01']);

        $this->runValidate();

        $this->assertStatusEqualsCode(Status::ok(), 'PAGUSO01');
    }

    public function testInvalidUsoCfdi()
    {
        $comprobante = $this->getComprobante();
        $comprobante->addReceptor(['UsoCFDI' => 'P02']);

        $this->runValidate();

        $this->assertStatusEqualsCode(Status::error(), 'PAGUSO01');
    }

    public function testInvalidNoReceptor()
    {
        $this->runValidate();

        $this->assertStatusEqualsCode(Status::error(), 'PAGUSO01');
    }
}
