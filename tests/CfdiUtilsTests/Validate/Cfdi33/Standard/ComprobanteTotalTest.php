<?php

namespace CfdiUtilsTests\Validate\Cfdi33\Standard;

use CfdiUtils\Validate\Cfdi33\Standard\ComprobanteTotal;
use CfdiUtils\Validate\Contracts\ValidatorInterface;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\Validate\Validate33TestCase;

final class ComprobanteTotalTest extends Validate33TestCase
{
    /** @var ComprobanteTotal */
    protected ValidatorInterface $validator;

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
     * @dataProvider providerTotalWithInvalidValue
     */
    public function testTotalWithInvalidValue(?string $value): void
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
     * @dataProvider providerTotalWithValidValues
     */
    public function testTotalWithCorrectValues(string $value): void
    {
        $this->comprobante->addAttributes([
            'Total' => $value,
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::ok(), 'TOTAL01');
    }
}
