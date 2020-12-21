<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos;

use CfdiUtils\Elements\Pagos10\Pagos as PagosElement;
use CfdiUtils\Nodes\Node;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\ComplementoPagos;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\Validate\ValidateTestCase;

class ComplementoPagosTest extends ValidateTestCase
{
    /** @var ComplementoPagos */
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new ComplementoPagos();
    }

    private function setUpComplemento(): PagosElement
    {
        $comprobante = $this->getComprobante();
        $comprobante['TipoDeComprobante'] = 'P';

        $pagos = new PagosElement();
        $comprobante->addComplemento($pagos);

        return $pagos;
    }

    public function testValidCaseWithComplemento()
    {
        $this->setUpComplemento();

        $this->runValidate();

        $this->assertStatusEqualsCode(Status::ok(), 'COMPPAG01');
        $this->assertStatusEqualsCode(Status::ok(), 'COMPPAG02');
        $this->assertStatusEqualsCode(Status::ok(), 'COMPPAG03');
    }

    public function testValidCaseWithoutComplemento()
    {
        $this->runValidate();

        $this->assertStatusEqualsCode(Status::ok(), 'COMPPAG01');
        $this->assertStatusEqualsCode(Status::none(), 'COMPPAG02');
        $this->assertStatusEqualsCode(Status::none(), 'COMPPAG03');
    }

    public function testWithoutComplemento()
    {
        $comprobante = $this->getComprobante();
        $comprobante['TipoDeComprobante'] = 'P';

        $this->runValidate();

        $this->assertStatusEqualsCode(Status::error(), 'COMPPAG01');
        $this->assertStatusEqualsCode(Status::none(), 'COMPPAG02');
        $this->assertStatusEqualsCode(Status::ok(), 'COMPPAG03');
    }

    public function testWithoutTipoDeComprobante()
    {
        $comprobante = $this->getComprobante();
        $comprobante->addComplemento(new PagosElement());

        $this->runValidate();

        $this->assertStatusEqualsCode(Status::error(), 'COMPPAG01');
        $this->assertStatusEqualsCode(Status::ok(), 'COMPPAG02');
        $this->assertStatusEqualsCode(Status::none(), 'COMPPAG03');
    }

    public function testWithInvalidComprobanteVersion()
    {
        $this->setUpComplemento();

        $this->comprobante['Version'] = '3.2';

        $this->runValidate();

        $this->assertStatusEqualsCode(Status::ok(), 'COMPPAG01');
        $this->assertStatusEqualsCode(Status::ok(), 'COMPPAG02');
        $this->assertStatusEqualsCode(Status::error(), 'COMPPAG03');
    }

    public function testWithInvalidComplementoVersion()
    {
        $complemento = $this->setUpComplemento();
        $complemento['Version'] = '0.9';

        $this->runValidate();

        $this->assertStatusEqualsCode(Status::ok(), 'COMPPAG01');
        $this->assertStatusEqualsCode(Status::error(), 'COMPPAG02');
        $this->assertStatusEqualsCode(Status::ok(), 'COMPPAG03');
    }

    public function testImpuestosMustNotExists()
    {
        $this->setUpComplemento();

        $this->runValidate();

        $this->assertStatusEqualsCode(Status::ok(), 'COMPPAG04');
    }

    public function testImpuestosMustNotExistsButExists()
    {
        $pagos = $this->setUpComplemento();
        $pagos->addChild(new Node('pago10:Impuestos'));

        $this->runValidate();

        $this->assertStatusEqualsCode(Status::error(), 'COMPPAG04');
    }
}
