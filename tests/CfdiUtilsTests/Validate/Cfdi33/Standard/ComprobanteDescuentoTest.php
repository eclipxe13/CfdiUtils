<?php

namespace CfdiUtilsTests\Validate\Cfdi33\Standard;

use CfdiUtils\Validate\Cfdi33\Standard\ComprobanteDescuento;
use CfdiUtils\Validate\Contracts\ValidatorInterface;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\Validate\Validate33TestCase;

final class ComprobanteDescuentoTest extends Validate33TestCase
{
    /** @var ComprobanteDescuento */
    protected ValidatorInterface $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new ComprobanteDescuento();
    }

    public function providerValidCases(): array
    {
        return[
            ['0', '1'],
            ['1', '1'],
            ['0.000000', '0.000001'],
            ['0', '0'],
            ['1.00', '1.01'],
        ];
    }

    /**
     * @dataProvider providerValidCases
     */
    public function testValidCases(string $descuento, string $subtotal): void
    {
        $this->comprobante->addAttributes([
            'Descuento' => $descuento,
            'SubTotal' => $subtotal,
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::ok(), 'DESCUENTO01');
    }

    public function providerInvalidCases(): array
    {
        return[
            ['', '0'],
            ['1', '0'],
            ['5', null],
            ['0.000001', '0.000000'],
            ['-1', '5'],
            ['-5', '5'],
        ];
    }

    /**
     * @dataProvider providerInvalidCases
     */
    public function testInvalidCases(string $descuento, ?string $subtotal): void
    {
        $this->comprobante->addAttributes([
            'Descuento' => $descuento,
            'SubTotal' => $subtotal,
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'DESCUENTO01');
    }

    public function testNoneCase(): void
    {
        $this->comprobante->addAttributes([
            'Descuento' => null,
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::none(), 'DESCUENTO01');
    }
}
