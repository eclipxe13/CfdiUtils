<?php

namespace CfdiUtilsTests\Validate\Cfdi33\Standard;

use CfdiUtils\Validate\Cfdi33\Standard\ConceptoDescuento;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\Validate\ValidateTestCase;

class ConceptoDescuentoTest extends ValidateTestCase
{
    /** @var ConceptoDescuento */
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new ConceptoDescuento();
    }

    public function providerValidCases()
    {
        return[
            ['', '0'],
            ['0', '1'],
            ['1', '1'],
            ['0.000000', '0.000001'],
            ['0', '0'],
            ['1.00', '1.01'],
        ];
    }

    /**
     * @param string $descuento
     * @param string $importe
     * @dataProvider providerValidCases
     */
    public function testValidCases($descuento, $importe)
    {
        $this->getComprobante()->addConcepto([
            'Descuento' => $descuento,
            'Importe' => $importe,
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::ok(), 'CONCEPDESC01');
    }

    public function providerInvalidCases()
    {
        return[
            ['1', '0'],
            ['5', null],
            ['0.000001', '0.000000'],
            ['-1', '5'],
            ['-5', '5'],
        ];
    }

    /**
     * @param string $descuento
     * @param string $importe
     * @dataProvider providerInvalidCases
     */
    public function testInvalidCases($descuento, $importe)
    {
        $this->getComprobante()->addConcepto(['Descuento' => '1', 'Importe' => '2']);
        $concepto = $this->getComprobante()->addConcepto([
            'Descuento' => $descuento,
            'Importe' => $importe,
        ]);
        $this->assertTrue($this->validator->conceptoHasInvalidDiscount($concepto));
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'CONCEPDESC01');
    }

    public function testNoneCase()
    {
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::none(), 'CONCEPDESC01');
    }
}
