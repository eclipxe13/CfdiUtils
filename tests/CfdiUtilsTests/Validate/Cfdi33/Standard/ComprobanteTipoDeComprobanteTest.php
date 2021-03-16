<?php

namespace CfdiUtilsTests\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\Node;
use CfdiUtils\Validate\Cfdi33\Standard\ComprobanteTipoDeComprobante;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\Validate\ValidateTestCase;

class ComprobanteTipoDeComprobanteTest extends ValidateTestCase
{
    /** @var  ComprobanteTipoDeComprobante */
    protected $validator;

    protected function setUp()
    {
        parent::setUp();
        $this->validator = new ComprobanteTipoDeComprobante();
    }

    public function providerTPN()
    {
        return [['T'], ['P'], ['N']];
    }

    /**
     * @param string $tipoDeComprobante
     * @dataProvider providerTPN
     */
    public function testValidTPN($tipoDeComprobante)
    {
        $this->comprobante->addAttributes([
            'TipoDeComprobante' => $tipoDeComprobante,
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::ok(), 'TIPOCOMP01');
        $this->assertStatusEqualsCode(Status::ok(), 'TIPOCOMP02');
        $this->assertStatusEqualsCode(Status::ok(), 'TIPOCOMP03');
    }

    /**
     * @param string $tipoDeComprobante
     * @dataProvider providerTPN
     */
    public function testInvalidTPN($tipoDeComprobante)
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
        $this->assertStatusEqualsCode(Status::error(), 'TIPOCOMP03');
    }

    public function providerTP()
    {
        return [['T'], ['P']];
    }

    /**
     * @param string $tipoDeComprobante
     * @dataProvider providerTP
     */
    public function testValidTP($tipoDeComprobante)
    {
        $this->comprobante->addAttributes([
            'TipoDeComprobante' => $tipoDeComprobante,
            'SubTotal' => '0',
            'Total' => '0.00',
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::ok(), 'TIPOCOMP04');
        $this->assertStatusEqualsCode(Status::ok(), 'TIPOCOMP05');
        $this->assertStatusEqualsCode(Status::ok(), 'TIPOCOMP06');
        $this->assertStatusEqualsCode(Status::ok(), 'TIPOCOMP07');
        $this->assertStatusEqualsCode(Status::ok(), 'TIPOCOMP08');
    }

    /**
     * @param string $tipoDeComprobante
     * @dataProvider providerTP
     */
    public function testInvalidTPMetodoPago($tipoDeComprobante)
    {
        $this->comprobante->addAttributes([
            'TipoDeComprobante' => $tipoDeComprobante,
            'MetodoPago' => '',
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'TIPOCOMP04');
    }

    /**
     * @param string $tipoDeComprobante
     * @dataProvider providerTP
     */
    public function testInvalidTPDescuentos($tipoDeComprobante)
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

    public function providerTPNonZero()
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
     * @param string $tipoDeComprobante
     * @param string|null $subtotal
     * @dataProvider providerTPNonZero
     */
    public function testInvalidSubTotal($tipoDeComprobante, $subtotal)
    {
        $this->comprobante->addAttributes([
            'TipoDeComprobante' => $tipoDeComprobante,
            'SubTotal' => $subtotal,
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'TIPOCOMP07');
    }

    /**
     * @param string $tipoDeComprobante
     * @param string|null $total
     * @dataProvider providerTPNonZero
     */
    public function testInvalidTotal($tipoDeComprobante, $total)
    {
        $this->comprobante->addAttributes([
            'TipoDeComprobante' => $tipoDeComprobante,
            'Total' => $total,
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'TIPOCOMP08');
    }

    public function providerIEN()
    {
        return [['I'], ['E'], ['N']];
    }

    /**
     * @param string $tipoDeComprobante
     * @dataProvider providerIEN
     */
    public function testValidIENValorUnitarioGreaterThanZero($tipoDeComprobante)
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

    public function providerIENWrongValue()
    {
        return $this->providerFullJoin(
            $this->providerIEN(),
            [[null], [''], ['0'], ['0.00'], ['0.0000001']]
        );
    }

    /**
     * @param string $tipoDeComprobante
     * @param string|null $wrongUnitValue
     * @dataProvider providerIENWrongValue
     */
    public function testInvalidIENValorUnitarioGreaterThanZero($tipoDeComprobante, $wrongUnitValue)
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
