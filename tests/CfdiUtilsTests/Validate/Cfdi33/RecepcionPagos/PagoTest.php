<?php
namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos;

use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pago;

class PagoTest extends ValidateComplementoPagosTestCase
{
    /** @var Pago */
    protected $validator;

    protected function setUp()
    {
        parent::setUp();
        $this->validator = new Pago();
    }

    public function testValidatorsCodes()
    {
        $expectedValidators = [];
        foreach (range(2, 22) as $i) {
            $expectedValidators[] = sprintf('PAGO%02d', $i);
        }
        $expectedValidators[] = 'PAGO30';

        $validators = $this->validator->getValidators();
        $validatorsCodes = [];
        foreach ($validators as $validator) {
            if ('' !== $validator->getCode()) {
                $validatorsCodes[] = $validator->getCode();
            }
        }

        $this->assertEquals($expectedValidators, $validatorsCodes);
    }
}
