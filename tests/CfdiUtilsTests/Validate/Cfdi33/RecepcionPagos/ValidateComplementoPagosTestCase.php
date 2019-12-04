<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos;

use CfdiUtils\Elements\Pagos10\Pagos as Pagos10;
use CfdiUtilsTests\Validate\ValidateTestCase;

abstract class ValidateComplementoPagosTestCase extends ValidateTestCase
{
    /** @var Pagos10 */
    protected $complemento;

    protected function setUp()
    {
        parent::setUp();

        $comprobante = $this->getComprobante();
        $comprobante['TipoDeComprobante'] = 'P';

        $this->complemento = new Pagos10();
        $comprobante->addComplemento($this->complemento);
    }

    public function testWithoutComplementoDidNotCreateAnyAssertion()
    {
        $this->getComprobante()->children()->removeAll();
        $this->runValidate();

        $this->assertCount(0, $this->asserts, sprintf(
            'The validator %s should not create any assert',
            get_class($this->validator)
        ));
    }
}
