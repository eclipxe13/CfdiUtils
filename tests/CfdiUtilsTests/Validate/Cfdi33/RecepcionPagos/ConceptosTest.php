<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos;

use CfdiUtils\Elements\Cfdi33\Concepto;
use CfdiUtils\Nodes\Node;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Conceptos;
use CfdiUtils\Validate\Status;

final class ConceptosTest extends ValidateComplementoPagosTestCase
{
    /** @var Conceptos */
    protected $validator;

    /** @var Concepto */
    protected $concepto;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new Conceptos();

        // setup a valid case and in the test change to make it fail
        $comprobante = $this->getComprobante();
        $this->concepto = $comprobante->addConcepto([
            'ClaveProdServ' => Conceptos::REQUIRED_CLAVEPRODSERV,
            'ClaveUnidad' => Conceptos::REQUIRED_CLAVEUNIDAD,
            'Descripcion' => Conceptos::REQUIRED_DESCRIPCION,
            'Cantidad' => Conceptos::REQUIRED_CANTIDAD,
            'ValorUnitario' => Conceptos::REQUIRED_VALORUNITARIO,
            'Importe' => Conceptos::REQUIRED_IMPORTE,
        ]);
    }

    public function testValidCase(): void
    {
        $this->runValidate();

        $this->assertStatusEqualsCode(Status::ok(), 'PAGCON01');
    }

    public function testConceptosNotExists(): void
    {
        $comprobante = $this->getComprobante();
        $comprobante->children()->remove($comprobante->getConceptos());

        $this->runValidate();

        $assert = $this->getAssertByCodeOrFail('PAGCON01');
        $this->assertStatusEqualsAssert(Status::error(), $assert);
        $this->assertStringContainsString('No se encontró el nodo Conceptos', $assert->getExplanation());
    }

    public function testConceptosZeroChildren(): void
    {
        $comprobante = $this->getComprobante();
        $comprobante->getConceptos()->children()->removeAll();

        $this->runValidate();

        $assert = $this->getAssertByCodeOrFail('PAGCON01');
        $this->assertStatusEqualsAssert(Status::error(), $assert);
        $this->assertStringContainsString('Se esperaba encontrar un solo hijo de conceptos', $assert->getExplanation());
    }

    public function testConceptosChildrenMoreThanOne(): void
    {
        $comprobante = $this->getComprobante();
        $comprobante->addConcepto();

        $this->runValidate();

        $assert = $this->getAssertByCodeOrFail('PAGCON01');
        $this->assertStatusEqualsAssert(Status::error(), $assert);
        $this->assertStringContainsString('Se esperaba encontrar un solo hijo de conceptos', $assert->getExplanation());
    }

    public function testConceptosChildIsNotConcepto(): void
    {
        $comprobante = $this->getComprobante();
        $conceptos = $comprobante->getConceptos();
        $conceptos->children()->removeAll();
        $conceptos->addChild(new Node('cfdi:foo'));

        $this->runValidate();

        $assert = $this->getAssertByCodeOrFail('PAGCON01');
        $this->assertStatusEqualsAssert(Status::error(), $assert);
        $this->assertStringContainsString('No se encontró el nodo Concepto', $assert->getExplanation());
    }

    public function testConceptoWithChildren(): void
    {
        $this->concepto->addChild(new Node('cfdi:foo'));

        $this->runValidate();

        $assert = $this->getAssertByCodeOrFail('PAGCON01');
        $this->assertStatusEqualsAssert(Status::error(), $assert);
        $this->assertStringContainsString('Se esperaba encontrar ningún hijo de concepto', $assert->getExplanation());
    }

    public function providerConceptoInvalidData(): array
    {
        $second = [
            [null],
            [''],
            ['_'],
        ];
        return static::providerFullJoin([
            ['ClaveProdServ'],
            ['Cantidad'],
            ['ClaveUnidad'],
            ['Descripcion'],
            ['ValorUnitario'],
            ['Importe'],
        ], $second);
    }

    /**
     * @param string $attribute
     * @param string|null $value
     * @dataProvider providerConceptoInvalidData
     */
    public function testConceptoInvalidData(string $attribute, ?string $value): void
    {
        $this->concepto[$attribute] = $value;

        $this->runValidate();

        $this->assertStatusEqualsCode(Status::error(), 'PAGCON01');
    }

    public function providerConceptoInvalidDataMustNotExists(): array
    {
        return [
            ['NoIdentificacion'],
            ['Unidad'],
            ['Descuento'],
        ];
    }

    /**
     * @param string $attribute
     * @dataProvider providerConceptoInvalidDataMustNotExists
     */
    public function testConceptoInvalidDataMustNotExists(string $attribute): void
    {
        $this->concepto[$attribute] = '';

        $this->runValidate();

        $this->assertStatusEqualsCode(Status::error(), 'PAGCON01');
    }
}
