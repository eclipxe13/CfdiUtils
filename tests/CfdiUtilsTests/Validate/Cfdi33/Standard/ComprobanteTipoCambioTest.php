<?php

namespace CfdiUtilsTests\Validate\Cfdi33\Standard;

use CfdiUtils\Validate\Cfdi33\Standard\ComprobanteTipoCambio;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\Validate\ValidateTestCase;

class ComprobanteTipoCambioTest extends ValidateTestCase
{
    /** @var ComprobanteTipoCambio */
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new ComprobanteTipoCambio();
    }

    public function providerMonedaWithValidValues(): array
    {
        return [
            ['MXN', '1', 'TIPOCAMBIO02', ['TIPOCAMBIO03', 'TIPOCAMBIO04']],
            ['MXN', '1.000000', 'TIPOCAMBIO02', ['TIPOCAMBIO03', 'TIPOCAMBIO04']],
            ['MXN', null, 'TIPOCAMBIO02', ['TIPOCAMBIO03', 'TIPOCAMBIO04']],
            ['XXX', null, 'TIPOCAMBIO03', ['TIPOCAMBIO02', 'TIPOCAMBIO04']],
            ['USD', '10.0', 'TIPOCAMBIO04', ['TIPOCAMBIO02', 'TIPOCAMBIO03']],
            ['USD', '20', 'TIPOCAMBIO04', ['TIPOCAMBIO02', 'TIPOCAMBIO03']],
            ['USD', '0005.10000', 'TIPOCAMBIO04', ['TIPOCAMBIO02', 'TIPOCAMBIO03']],
            ['USD', '123456789012345678.0', 'TIPOCAMBIO04', ['TIPOCAMBIO02', 'TIPOCAMBIO03']],
            ['USD', '0.123456', 'TIPOCAMBIO04', ['TIPOCAMBIO02', 'TIPOCAMBIO03']],
        ];
    }

    /**
     * @param string $moneda
     * @param string|null $tipoCambio
     * @param string $ok
     * @param string[] $nones
     * @dataProvider providerMonedaWithValidValues
     */
    public function testMonedaWithValidValues(string $moneda, ?string $tipoCambio, string $ok, array $nones)
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

    public function providerNoMonedaOrEmpty(): array
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
     * @param string|null $moneda
     * @param string|null $tipoCambio
     * @dataProvider providerNoMonedaOrEmpty
     */
    public function testNoMonedaOrEmpty(?string $moneda, ?string $tipoCambio)
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

    public function providerMonedaWithInvalidValues(): array
    {
        return [
            ['MXN', '', 'TIPOCAMBIO02', ['TIPOCAMBIO03', 'TIPOCAMBIO04']],
            ['MXN', '1.000001', 'TIPOCAMBIO02', ['TIPOCAMBIO03', 'TIPOCAMBIO04']],
            ['MXN', '0.999999', 'TIPOCAMBIO02', ['TIPOCAMBIO03', 'TIPOCAMBIO04']],
            ['MXN', '10.0', 'TIPOCAMBIO02', ['TIPOCAMBIO03', 'TIPOCAMBIO04']],
            ['XXX', '', 'TIPOCAMBIO03', ['TIPOCAMBIO02', 'TIPOCAMBIO04']],
            ['XXX', '10.0', 'TIPOCAMBIO03', ['TIPOCAMBIO02', 'TIPOCAMBIO04']],
            ['USD', null, 'TIPOCAMBIO04', ['TIPOCAMBIO02', 'TIPOCAMBIO03']],
            ['USD', '', 'TIPOCAMBIO04', ['TIPOCAMBIO02', 'TIPOCAMBIO03']],
            ['USD', 'abc', 'TIPOCAMBIO04', ['TIPOCAMBIO02', 'TIPOCAMBIO03']],
            ['USD', '1234567890123456789.0', 'TIPOCAMBIO04', ['TIPOCAMBIO02', 'TIPOCAMBIO03']],
            ['USD', '0.1234567', 'TIPOCAMBIO04', ['TIPOCAMBIO02', 'TIPOCAMBIO03']],
            ['USD', '0.', 'TIPOCAMBIO04', ['TIPOCAMBIO02', 'TIPOCAMBIO03']],
            ['USD', '.0', 'TIPOCAMBIO04', ['TIPOCAMBIO02', 'TIPOCAMBIO03']],
            ['USD', '0..0', 'TIPOCAMBIO04', ['TIPOCAMBIO02', 'TIPOCAMBIO03']],
            ['USD', '0.0.0', 'TIPOCAMBIO04', ['TIPOCAMBIO02', 'TIPOCAMBIO03']],
        ];
    }

    /**
     * @param string $moneda
     * @param string|null $tipoCambio
     * @param string $error
     * @param string[] $nones
     * @dataProvider providerMonedaWithInvalidValues
     */
    public function testMonedaWithInvalidValues(string $moneda, ?string $tipoCambio, string $error, array $nones)
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
