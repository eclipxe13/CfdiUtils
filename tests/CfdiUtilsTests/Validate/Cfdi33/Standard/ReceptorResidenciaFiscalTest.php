<?php

namespace CfdiUtilsTests\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\Node;
use CfdiUtils\Validate\Cfdi33\Standard\ReceptorResidenciaFiscal;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\Validate\Validate33TestCase;

final class ReceptorResidenciaFiscalTest extends Validate33TestCase
{
    /** @var  ReceptorResidenciaFiscal */
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new ReceptorResidenciaFiscal();
    }

    public function providerValidCases(): array
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
     * @dataProvider providerValidCases
     */
    public function testValidCase(
        ?string $receptorRfc,
        ?string $residenciaFiscal,
        ?string $numRegIdTrib,
        bool $putComercioExterior,
        string $ok
    ): void {
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

    public function providerInvalidCases(): array
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
     * @dataProvider providerInvalidCases
     */
    public function testInvalidCase(
        ?string $receptorRfc,
        ?string $residenciaFiscal,
        ?string $numRegIdTrib,
        bool $putComercioExterior,
        string $error
    ): void {
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

    public function testValidCaseWithoutReceptorNode(): void
    {
        $this->runValidate();
        $this->assertFalse($this->asserts->hasErrors());
    }
}
