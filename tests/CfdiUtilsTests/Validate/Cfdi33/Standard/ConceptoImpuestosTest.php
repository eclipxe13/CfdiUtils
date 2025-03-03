<?php

namespace CfdiUtilsTests\Validate\Cfdi33\Standard;

use CfdiUtils\Elements\Cfdi33\Comprobante;
use CfdiUtils\Validate\Cfdi33\Standard\ConceptoImpuestos;
use CfdiUtils\Validate\Contracts\ValidatorInterface;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\Validate\Validate33TestCase;

final class ConceptoImpuestosTest extends Validate33TestCase
{
    /** @var ConceptoImpuestos */
    protected ValidatorInterface $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new ConceptoImpuestos();
    }

    public function testInvalidCaseNoRetencionOrTraslado(): void
    {
        $comprobante = $this->validComprobante();
        $comprobante->addConcepto()->getImpuestos();
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'CONCEPIMPC01');
    }

    public function providerInvalidBaseTraslado(): array
    {
        return [
            ['0'],
            ['0.0000001'],
            ['-1'],
            ['foo'],
            ['0.0.0.0'],
        ];
    }

    /**
     * @dataProvider providerInvalidBaseTraslado
     */
    public function testTrasladoHasBaseGreaterThanZeroInvalidCase(string $base): void
    {
        $comprobante = $this->validComprobante();
        $comprobante->addConcepto()->addTraslado(['Base' => $base]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'CONCEPIMPC02');
    }

    public function providerTrasladoTipoFactorExento(): array
    {
        return[
            ['1', '1'],
            [null, '1'],
            ['1', null],
        ];
    }

    /**
     * @dataProvider providerTrasladoTipoFactorExento
     */
    public function testTrasladoTipoFactorExentoInvalidCase(?string $tasaOCuota, ?string $importe): void
    {
        $comprobante = $this->validComprobante();
        $comprobante->addConcepto()->addTraslado([
            'TipoFactor' => 'Exento',
            'TasaOCuota' => $tasaOCuota,
            'Importe' => $importe,
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'CONCEPIMPC03');
    }

    public function providerTrasladosTipoFactorTasaOCuotaInvalidCase(): array
    {
        return $this->providerFullJoin(
            [['Tasa'], ['Cuota']], // tipoFactor
            [['1'], [''], [null]], // tasaOCuota
            [[''], [null]]  // importe
        );
    }

    /**
     * @dataProvider providerTrasladosTipoFactorTasaOCuotaInvalidCase
     */
    public function testTrasladosTipoFactorTasaOCuotaInvalidCase(
        string $tipoFactor,
        ?string $tasaOCuota,
        ?string $importe,
    ): void {
        $comprobante = $this->validComprobante();
        $comprobante->addConcepto()->addTraslado([
            'TipoFactor' => $tipoFactor,
            'TasaOCuota' => $tasaOCuota,
            'Importe' => $importe,
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'CONCEPIMPC04');
    }

    public function providerInvalidBaseRetencion(): array
    {
        return[
            ['0'],
            ['0.0000001'],
            ['-1'],
            ['foo'],
            ['0.0.0.0'],
        ];
    }

    /**
     * @dataProvider providerInvalidBaseTraslado
     */
    public function testRetencionesHasBaseGreaterThanZeroInvalidCase(string $base): void
    {
        $comprobante = $this->validComprobante();
        $comprobante->addConcepto()->addRetencion(['Base' => $base]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'CONCEPIMPC05');
    }

    public function testInvalidCaseRetencionTipoFactorExento(): void
    {
        $comprobante = $this->validComprobante();
        $comprobante->addConcepto()->addRetencion(['TipoFactor' => 'Exento']);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'CONCEPIMPC06');
    }

    public function testValidComprobante(): void
    {
        $this->validComprobante();
        $this->runValidate();
        $this->assertFalse($this->asserts->hasErrors());
    }

    private function validComprobante(): Comprobante
    {
        /** @var Comprobante $comprobante */
        $comprobante = $this->comprobante;
        $comprobante->addConcepto();
        $comprobante->addConcepto()->multiTraslado([
            'TipoFactor' => 'Exento',
            'Base' => '123.45',
        ], [
            'Base' => '123.45',
            'TipoFactor' => 'Tasa',
            'TasaOCuota' => '0.160000',
            'Importe' => '19.75',
        ])->multiRetencion([
            'Base' => '0.000001',
            'TipoFactor' => 'Tasa',
            'TasaOCuota' => '0.02',
            'Importe' => '1.23',
        ], [
            'Base' => '123.45',
            'TipoFactor' => 'Cuota',
        ]);
        return $comprobante;
    }
}
