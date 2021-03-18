<?php

namespace CfdiUtilsTests;

use CfdiUtils\Cfdi;
use CfdiUtils\QuickReader\QuickReader;

final class CfdiQuickReaderTest extends TestCase
{
    /** @var QuickReader */
    private $comprobante;

    protected function setUp(): void
    {
        parent::setUp();
        $contents = strval(file_get_contents($this->utilAsset('cfdi33-real.xml')));
        $this->comprobante = Cfdi::newFromString($contents)->getQuickReader();
    }

    public function testAccessToAttribute()
    {
        $this->assertSame('3.3', $this->comprobante['version']);
    }

    public function testAccessToNestedAttributeFirstLevel()
    {
        $this->assertSame('273.46', $this->comprobante->impuestos['totalImpuestosTrasladados']);
    }

    public function testSumIvasInsideTraslados()
    {
        $iva = 0;
        foreach (($this->comprobante->impuestos->traslados)('traslado') as $traslado) {
            if ('002' === $traslado['iMpUeStO']) {
                $iva = $iva + floatval($traslado['Importe']);
            }
        }

        $this->assertEqualsWithDelta(273.46, $iva, 0.001);
    }

    public function testAccessToNestedAttributeSecondLevel()
    {
        // the attribute is named originally: TotaldeTraslados
        $this->assertSame('27.43', $this->comprobante->complemento->impuestosLocales['TotalDeTraslados']);
    }

    public function testIterateOverChildren()
    {
        $sum = 0;
        /*
         * You can do these 4 ways that are the same:
         *
         * $conceptos = $this->comprobante->conceptos;
         * foreach ($conceptos('concepto') as $concepto) {
         *
         * foreach (($this->comprobante->conceptos)('concepto') as $concepto) {
         *
         * foreach ($this->comprobante->conceptos->__invoke('concepto') as $concepto) {
         */
        foreach (($this->comprobante->conceptos)('concepto') as $concepto) {
            $sum += (float) $concepto['importe'];
        }

        $this->assertEqualsWithDelta(1709.12, $sum, 0.001);
    }
}
