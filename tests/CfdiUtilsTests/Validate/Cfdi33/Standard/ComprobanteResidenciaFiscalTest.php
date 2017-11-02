<?php
namespace CfdiUtilsTests\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\Node;
use CfdiUtils\Validate\Cfdi33\Standard\ComprobanteResidenciaFiscal;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\Validate\ValidateTestCase;

class ComprobanteResidenciaFiscalTest extends ValidateTestCase
{
    /** @var  ComprobanteResidenciaFiscal */
    protected $validator;

    protected function setUp()
    {
        parent::setUp();
        $this->validator = new ComprobanteResidenciaFiscal();
    }

    public function providerValidCases()
    {
        return [
            // RESFISC01: Si el RFC no es XEXX010101000 entonces la residencia fiscal no debe existir
            [null, null, null, false, 'RESFISC01'],
            ['', null, null, false, 'RESFISC01'],
            ['XXXXXXXXXXXX', null, null, false, 'RESFISC01'],
            // RESFISC02: Si el RFC sí es XEXX010101000 y existe el complemento de comercio exterior
            // entonces la residencia fiscal debe establecerse y no puede ser "MEX"
            ['XEXX010101000', 'XXX', null, true, 'RESFISC02'],
            // RESFISC03: Si el RFC sí es XEXX010101000 y se registró el número de registro de identificación fiscal
            // entonces la residencia fiscal debe establecerse y no puede ser "MEX"
            ['XEXX010101000', 'XXX', '1234', false, 'RESFISC03'],
        ];
    }

    /**
     * @param $receptorRfc
     * @param $residenciaFiscal
     * @param $numRegIdTrib
     * @param $putComercioExterior
     * @param $ok
     * @dataProvider providerValidCases
     */
    public function testValidCase($receptorRfc, $residenciaFiscal, $numRegIdTrib, $putComercioExterior, $ok)
    {
        $this->comprobante->addChild(new Node('cfdi:Receptor', [
            'Rfc' => $receptorRfc,
            'ResidenciaFiscal' => $residenciaFiscal,
            'NumRegIdTrib' => $numRegIdTrib,
        ]));
        if ($putComercioExterior) {
            $this->comprobante->addChild(new Node('cfdi:Complemento', [], [
                new Node('cce11:ComercioExterior'),
            ]));
        }
        $this->runValidate();
        $this->assertFalse($this->asserts->hasErrors());
        $this->assertStatusEqualsCode(Status::ok(), $ok);
    }

    public function providerInvalidCases()
    {
        return [
            // RESFISC01: Si el RFC no es XEXX010101000 entonces la residencia fiscal no debe existir
            [null, '', null, false, 'RESFISC01'],
            ['', '', null, false, 'RESFISC01'],
            ['XXXXXXXXXXXX', '', null, false, 'RESFISC01'],
            [null, 'GER', null, false, 'RESFISC01'],
            ['', 'GER', null, false, 'RESFISC01'],
            ['XXXXXXXXXXXX', 'GER', null, false, 'RESFISC01'],
            // RESFISC02: Si el RFC sí es XEXX010101000 y existe el complemento de comercio exterior
            // entonces la residencia fiscal debe establecerse y no puede ser "MEX"
            ['XEXX010101000', null, null, true, 'RESFISC02'],
            ['XEXX010101000', '', null, true, 'RESFISC02'],
            ['XEXX010101000', 'MEX', null, true, 'RESFISC02'],
            // RESFISC03: Si el RFC sí es XEXX010101000 y se registró el número de registro de identificación fiscal
            // entonces la residencia fiscal debe establecerse y no puede ser "MEX"
            ['XEXX010101000', null, '1234', false, 'RESFISC03'],
            ['XEXX010101000', '', '1234', false, 'RESFISC03'],
            ['XEXX010101000', 'MEX', '1234', false, 'RESFISC03'],
        ];
    }

    /**
     * @param $receptorRfc
     * @param $residenciaFiscal
     * @param $numRegIdTrib
     * @param $putComercioExterior
     * @param $error
     * @dataProvider providerinValidCases
     */
    public function testInvalidCase($receptorRfc, $residenciaFiscal, $numRegIdTrib, $putComercioExterior, $error)
    {
        $this->comprobante->addChild(new Node('cfdi:Receptor', [
            'Rfc' => $receptorRfc,
            'ResidenciaFiscal' => $residenciaFiscal,
            'NumRegIdTrib' => $numRegIdTrib,
        ]));
        if ($putComercioExterior) {
            $this->comprobante->addChild(new Node('cfdi:Complemento', [], [
                new Node('cce11:ComercioExterior'),
            ]));
        }
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), $error);
    }

    public function testValidCaseWithoutReceptorNode()
    {
        $this->runValidate();
        $this->assertFalse($this->asserts->hasErrors());
    }
}
