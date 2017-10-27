<?php
namespace CfdiUtilsTests\Validate\Cfdi33\Standard;

use CfdiUtils\Validate\Cfdi33\Standard\ComprobanteTipoCambio;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\Validate\ValidateTestCase;

class ComprobanteTipoCambioTest extends ValidateTestCase
{
    /** @var ComprobanteTipoCambio */
    protected $validator;

    protected function setUp()
    {
        parent::setUp();
        $this->validator = new ComprobanteTipoCambio();
    }

    public function providerMonedaWithValidValues()
    {
        return [
            ['MXN', '1', 'TIPOCAMBIO02', ['TIPOCAMBIO03', 'TIPOCAMBIO04']],
            ['MXN', null, 'TIPOCAMBIO02', ['TIPOCAMBIO03', 'TIPOCAMBIO04']],
            ['XXX', null, 'TIPOCAMBIO03', ['TIPOCAMBIO02', 'TIPOCAMBIO04']],
            ['USD', '10.0', 'TIPOCAMBIO04', ['TIPOCAMBIO02', 'TIPOCAMBIO03']],
        ];
    }

    /**
     * @param $moneda
     * @param $tipoCambio
     * @param $ok
     * @param $nones
     * @dataProvider providerMonedaWithValidValues
     */
    public function testMonedaWithValidValues($moneda, $tipoCambio, $ok, $nones)
    {
        $this->comprobante->addAttributes([
            'Moneda' => $moneda,
            'TipoCambio' => $tipoCambio,
        ]);
        $this->runValidate();

        $this->assertStatusEqualsCode(Status::ok(), 'TIPOCAMBIO01');
        $this->assertStatusEqualsCode(Status::ok(), $ok);
        foreach ($nones as $none) {
            $this->assertStatusEqualsCode(Status::none(), $none);
        }
    }

    public function providerNoMonedaOrEmpty()
    {
        return [
            [null, null],
            [null, ''],
            [null, '18.9000'],
            ['', null],
            ['', ''],
            ['', '18.9000'],
        ];
    }

    /**
     * @param $moneda
     * @param $tipoCambio
     * @dataProvider providerNoMonedaOrEmpty
     */
    public function testNoMonedaOrEmpty($moneda, $tipoCambio)
    {
        $this->comprobante->addAttributes([
            'Moneda' => $moneda,
            'TipoCambio' => $tipoCambio,
        ]);

        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'TIPOCAMBIO01');
        $this->assertStatusEqualsCode(Status::none(), 'TIPOCAMBIO02');
        $this->assertStatusEqualsCode(Status::none(), 'TIPOCAMBIO03');
        $this->assertStatusEqualsCode(Status::none(), 'TIPOCAMBIO04');
    }

    public function providerMonedaWithInvalidValues()
    {
        return [
            ['MXN', '', 'TIPOCAMBIO02', ['TIPOCAMBIO03', 'TIPOCAMBIO04']],
            ['MXN', '10.0', 'TIPOCAMBIO02', ['TIPOCAMBIO03', 'TIPOCAMBIO04']],
            ['XXX', '', 'TIPOCAMBIO03', ['TIPOCAMBIO02', 'TIPOCAMBIO04']],
            ['XXX', '10.0', 'TIPOCAMBIO03', ['TIPOCAMBIO02', 'TIPOCAMBIO04']],
            ['USD', null, 'TIPOCAMBIO04', ['TIPOCAMBIO02', 'TIPOCAMBIO03']],
            ['USD', '', 'TIPOCAMBIO04', ['TIPOCAMBIO02', 'TIPOCAMBIO03']],
        ];
    }

    /**
     * @param $moneda
     * @param $tipoCambio
     * @param $error
     * @param $nones
     * @dataProvider providerMonedaWithInvalidValues
     */
    public function testMonedaWithInvalidValues($moneda, $tipoCambio, $error, $nones)
    {
        $this->comprobante->addAttributes([
            'Moneda' => $moneda,
            'TipoCambio' => $tipoCambio,
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::ok(), 'TIPOCAMBIO01');
        $this->assertStatusEqualsCode(Status::error(), $error);
        foreach ($nones as $none) {
            $this->assertStatusEqualsCode(Status::none(), $none);
        }
    }
}
