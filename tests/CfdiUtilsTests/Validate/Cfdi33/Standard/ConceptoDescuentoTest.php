<?php
namespace CfdiUtilsTests\Validate\Cfdi33\Standard;

use CfdiUtils\Validate\Cfdi33\Standard\ConceptoDescuento;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\Validate\ValidateTestCase;

class ConceptoDescuentoTest extends ValidateTestCase
{
    /** @var ConceptoDescuento */
    protected $validator;

    protected function setUp()
    {
        parent::setUp();
        $this->validator = new ConceptoDescuento();
    }

    public function providerValidCases()
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
     * @param $descuento
     * @param $subtotal
     * @dataProvider providerValidCases
     */
    public function testValidCases($descuento, $subtotal)
    {
        $this->comprobante->addAttributes([
            'Descuento' => $descuento,
            'SubTotal' => $subtotal,
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::ok(), 'CONCEPDESC01');
    }

    public function providerInvalidCases()
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
     * @param $descuento
     * @param $subtotal
     * @dataProvider providerInvalidCases
     */
    public function testInvalidCases($descuento, $subtotal)
    {
        $this->comprobante->addAttributes([
            'Descuento' => $descuento,
            'SubTotal' => $subtotal,
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'CONCEPDESC01');
    }

    public function testNoneCase()
    {
        $this->comprobante->addAttributes([
            'Descuento' => null,
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::none(), 'CONCEPDESC01');
    }
}
