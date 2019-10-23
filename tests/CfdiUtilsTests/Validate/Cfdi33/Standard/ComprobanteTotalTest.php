<?php

namespace CfdiUtilsTests\Validate\Cfdi33\Standard;

use CfdiUtils\Validate\Cfdi33\Standard\ComprobanteTotal;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\Validate\ValidateTestCase;

class ComprobanteTotalTest extends ValidateTestCase
{
    /** @var ComprobanteTotal */
    protected $validator;

    protected function setUp()
    {
        parent::setUp();
        $this->validator = new ComprobanteTotal();
    }

    public function providerTotalWithInvalidValue()
    {
        return [
            'empty' => [''],
            'missing' => [null],
            'not a number' => ['foo'],
            '1.2e3' => ['1.2e3'],
            ['0.'],
            ['.0'],
            ['0..0'],
            ['0.0.0'],
            ['-0.0001'],
        ];
    }

    /**
     * @param string|null $value
     * @dataProvider providerTotalWithInvalidValue
     */
    public function testTotalWithInvalidValue($value)
    {
        $this->comprobante->addAttributes([
            'Total' => $value,
        ]);

        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'TOTAL01');
    }

    public function providerTotalWithValidValues()
    {
        return [
            '0' => ['0'],
            '0.0' => ['0.0'],
            '123.45' => ['123.45'],
        ];
    }

    /**
     * @param string|null $value
     * @dataProvider providerTotalWithValidValues
     */
    public function testTotalWithCorrectValues($value)
    {
        $this->comprobante->addAttributes([
            'Total' => $value,
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::ok(), 'TOTAL01');
    }
}
