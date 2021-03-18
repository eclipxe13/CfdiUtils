<?php

namespace CfdiUtilsTests\Validate\Cfdi33\Standard;

use CfdiUtils\Validate\Cfdi33\Standard\ComprobanteTotal;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\Validate\ValidateTestCase;

final class ComprobanteTotalTest extends ValidateTestCase
{
    /** @var ComprobanteTotal */
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new ComprobanteTotal();
    }

    public function providerTotalWithInvalidValue(): array
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
    public function testTotalWithInvalidValue(?string $value)
    {
        $this->comprobante->addAttributes([
            'Total' => $value,
        ]);

        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'TOTAL01');
    }

    public function providerTotalWithValidValues(): array
    {
        return [
            '0' => ['0'],
            '0.0' => ['0.0'],
            '123.45' => ['123.45'],
        ];
    }

    /**
     * @param string $value
     * @dataProvider providerTotalWithValidValues
     */
    public function testTotalWithCorrectValues(string $value)
    {
        $this->comprobante->addAttributes([
            'Total' => $value,
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::ok(), 'TOTAL01');
    }
}
