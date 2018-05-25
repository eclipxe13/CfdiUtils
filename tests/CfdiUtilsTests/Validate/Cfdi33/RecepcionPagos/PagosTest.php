<?php
namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos;

use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos;
use CfdiUtils\Validate\Status;

class PagosTest extends ValidateComplementoPagosTestCase
{
    /** @var Pagos */
    protected $validator;

    protected function setUp()
    {
        parent::setUp();
        $this->validator = new Pagos();
    }

    public function testValidCase()
    {
        $this->complemento->addPago();
        $this->runValidate();

        $this->assertStatusEqualsCode(Status::ok(), 'PAGOS01');
    }

    public function testWithoutNodes()
    {
        $this->runValidate();

        $this->assertStatusEqualsCode(Status::error(), 'PAGOS01');
    }
}
