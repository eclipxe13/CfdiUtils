<?php

namespace CfdiUtilsTests\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\Node;
use CfdiUtils\Validate\Cfdi33\Standard\ComprobanteTipoDeComprobante;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\Validate\Validate33TestCase;

final class ComprobanteTipoDeComprobanteTest extends Validate33TestCase
{
    /** @var  ComprobanteTipoDeComprobante */
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new ComprobanteTipoDeComprobante();
    }

    public function providerTPN(): array
    {
        return [['T'], ['P'], ['N']];
    }

    /**
     * @dataProvider providerTPN
     */
    public function testValidTPN(string $tipoDeComprobante): void
    {
        $this->comprobante->addAttributes([
            'TipoDeComprobante' => $tipoDeComprobante,
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::ok(), 'TIPOCOMP01');
        $this->assertStatusEqualsCode(Status::ok(), 'TIPOCOMP02');
    }

    /**
     * @dataProvider providerTPN
     */
    public function testInvalidTPN(string $tipoDeComprobante): void
    {
        $this->comprobante->addAttributes([
            'TipoDeComprobante' => $tipoDeComprobante,
            'CondicionesDePago' => '',
            'FormaPago' => '',
            'MetodoPago' => '',
        ]);
        $this->comprobante->addChild(new Node('cfdi:Impuestos'));
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'TIPOCOMP01');
        $this->assertStatusEqualsCode(Status::error(), 'TIPOCOMP02');
    }

    public function providerTP(): array
    {
        return [['T'], ['P']];
    }

    /**
     * @dataProvider providerTP
     */
    public function testValidTP(string $tipoDeComprobante): void
    {
        $this->comprobante->addAttributes([
            'TipoDeComprobante' => $tipoDeComprobante,
            'FormaPago' => null, // set to null to make clear that it must not exists
            'MetodoPago' => null, // set to null to make clear that it must not exists
            'SubTotal' => '0',
            'Total' => '0.00',
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::ok(), 'TIPOCOMP03');
        $this->assertStatusEqualsCode(Status::ok(), 'TIPOCOMP04');
        $this->assertStatusEqualsCode(Status::ok(), 'TIPOCOMP05');
        $this->assertStatusEqualsCode(Status::ok(), 'TIPOCOMP06');
        $this->assertStatusEqualsCode(Status::ok(), 'TIPOCOMP07');
        $this->assertStatusEqualsCode(Status::ok(), 'TIPOCOMP08');
    }

    /**
     * @dataProvider providerTP
     */
    public function testInvalidTP(string $tipoDeComprobante): void
    {
        $this->comprobante->addAttributes([
            'TipoDeComprobante' => $tipoDeComprobante,
            'FormaPago' => '',
            'MetodoPago' => '',
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'TIPOCOMP03');
        $this->assertStatusEqualsCode(Status::error(), 'TIPOCOMP04');
    }

    /**
     * @dataProvider providerTP
     */
    public function testInvalidTPDescuentos(string $tipoDeComprobante): void
    {
        $this->comprobante->addAttributes([
            'TipoDeComprobante' => $tipoDeComprobante,
            'Descuento' => '',
        ]);
        $this->comprobante->addChild(new Node('cfdi:Conceptos', [], [
            new Node('cfdi:Concepto'),
            new Node('cfdi:Concepto', ['Descuento' => '']),
        ]));
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'TIPOCOMP05');
        $this->assertStatusEqualsCode(Status::error(), 'TIPOCOMP06');
    }

    public function providerTPNonZero(): array
    {
        $types = [
            ['T'],
            ['P'],
        ];
        $values = [
            [null],
            [''],
            ['0.000001'],
            ['123.45'],
            ['foo'],
        ];
        return $this->providerFullJoin($types, $values);
    }

    /**
     * @dataProvider providerTPNonZero
     */
    public function testInvalidSubTotal(string $tipoDeComprobante, ?string $subtotal): void
    {
        $this->comprobante->addAttributes([
            'TipoDeComprobante' => $tipoDeComprobante,
            'SubTotal' => $subtotal,
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'TIPOCOMP07');
    }

    /**
     * @dataProvider providerTPNonZero
     */
    public function testInvalidTotal(string $tipoDeComprobante, ?string $total): void
    {
        $this->comprobante->addAttributes([
            'TipoDeComprobante' => $tipoDeComprobante,
            'Total' => $total,
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'TIPOCOMP08');
    }

    public function providerIEN(): array
    {
        return [['I'], ['E'], ['N']];
    }

    /**
     * @dataProvider providerIEN
     */
    public function testValidIENValorUnitarioGreaterThanZero(string $tipoDeComprobante): void
    {
        $this->comprobante->addAttributes([
            'TipoDeComprobante' => $tipoDeComprobante,
        ]);
        $this->comprobante->addChild(new Node('cfdi:Conceptos', [], [
            new Node('cfdi:Concepto', ['ValorUnitario' => '123.45']),
            new Node('cfdi:Concepto', ['ValorUnitario' => '0.000001']),
        ]));
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::ok(), 'TIPOCOMP09');
    }

    public function providerIENWrongValue(): array
    {
        return $this->providerFullJoin(
            $this->providerIEN(),
            [[null], [''], ['0'], ['0.00'], ['0.0000001']]
        );
    }

    /**
     * @dataProvider providerIENWrongValue
     */
    public function testInvalidIENValorUnitarioGreaterThanZero(string $tipoDeComprobante, ?string $wrongUnitValue): void
    {
        $this->comprobante->addAttributes([
            'TipoDeComprobante' => $tipoDeComprobante,
        ]);
        $this->comprobante->addChild(new Node('cfdi:Conceptos', [], [
            new Node('cfdi:Concepto', ['ValorUnitario' => '123.45']),
            new Node('cfdi:Concepto', [
                'ValorUnitario' => $wrongUnitValue,
            ]),
        ]));
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'TIPOCOMP09');
    }
}
