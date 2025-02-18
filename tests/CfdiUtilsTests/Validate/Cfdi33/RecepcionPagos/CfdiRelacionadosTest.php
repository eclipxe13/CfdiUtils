<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos;

use CfdiUtils\Validate\Cfdi33\RecepcionPagos\CfdiRelacionados;
use CfdiUtils\Validate\Status;

final class CfdiRelacionadosTest extends ValidateComplementoPagosTestCase
{
    /** @var CfdiRelacionados */
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new CfdiRelacionados();
    }

    public function testValidTipoRelacion(): void
    {
        $comprobante = $this->getComprobante();
        $comprobante->addCfdiRelacionados(['TipoRelacion' => '04']);

        $this->runValidate();

        $this->assertStatusEqualsCode(Status::ok(), 'PAGREL01');
    }

    public function testInvalidTipoRelacion(): void
    {
        $comprobante = $this->getComprobante();
        $comprobante->addCfdiRelacionados(['TipoRelacion' => 'XX']);

        $this->runValidate();

        $this->assertStatusEqualsCode(Status::error(), 'PAGREL01');
    }

    public function testWithoutCfdiRelacionados(): void
    {
        $this->runValidate();

        $this->assertStatusEqualsCode(Status::none(), 'PAGREL01');
    }
}
