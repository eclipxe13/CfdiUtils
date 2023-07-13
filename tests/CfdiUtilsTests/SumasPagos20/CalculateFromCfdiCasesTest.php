<?php

namespace CfdiUtilsTests\SumasPagos20;

use CfdiUtils\Cfdi;
use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\SumasPagos20\Calculator;
use CfdiUtils\SumasPagos20\Decimal;
use CfdiUtils\SumasPagos20\Pago;
use CfdiUtilsTests\TestCase;
use LogicException;

final class CalculateFromCfdiCasesTest extends TestCase
{
    /** @return array<string, array{string}> */
    public function providerPredefinedCfdi(): array
    {
        return [
            /**
             * @see \CfdiUtilsTests\CreateComprobantePagos40CaseTest
             * @see file://tests/assets/created-cfdi40-pago20-valid.xml
             */
            'created-cfdi40-pago20-valid.xml' => [self::utilAsset('created-cfdi40-pago20-valid.xml')],
            /**
             * 1 pago MXN pagando a 4 doctos MXN con tasa 16, 0 y exento.
             * 1 pago USD pagando a 1 docto MXN con tasa 16.
             *
             * @see file://tests/assets/pagos20-calculator/001.xml
             */
            'pagos20-calculator/001.xml' => [self::utilAsset('pagos20-calculator/001.xml')],
            /**
             * 1 Pago en MXN, con 1 documento relacionado MXN, con impuestos:
             * - ISR Retenido
             * - IVA Retenido
             * - IVA Trasladado 16%
             * - IEPS Trasladado 53%
             *
             * @see file://tests/assets/pagos20-calculator/002.xml
             */
            'pagos20-calculator/002.xml' => [self::utilAsset('pagos20-calculator/002.xml')],
            /**
             * 2 Pago en MXN, con 1 documento relacionado MXN cada uno, con impuestos:
             * - IVA Retenido
             * - ISR Retenido
             * - IVA Trasladado 16%
             *
             * @see file://tests/assets/pagos20-calculator/003.xml
             */
            'pagos20-calculator/003.xml' => [self::utilAsset('pagos20-calculator/003.xml')],
            /**
             * 1 Pago en MXN, 43 documentos relacionados en USD con impuestos de traslado IVA 16%
             * El cálculo de la sumatoria de impuestos es sensible a truncado o redondeo
             *
             * @see file://tests/assets/pagos20-calculator/004.xml
             */
            'pagos20-calculator/004.xml' => [self::utilAsset('pagos20-calculator/004.xml')],
            /**
             * Este pago incluye únicamente CFDI que no son objetos de pago. No tiene impuestos.
             *
             * @see file://tests/assets/pagos20-calculator/005.xml
             */
            'pagos20-calculator/005.xml' => [self::utilAsset('pagos20-calculator/005.xml')],
            /**
             * 3 Pagos, cada uno con 1 documento relacionado, IVA16, IVA0 e IVA Exento
             * El cálculo de la sumatoria de impuestos es sensible a truncado o redondeo
             *
             * @see file://tests/assets/pagos20-calculator/006.xml
             */
            'pagos20-calculator/006.xml' => [self::utilAsset('pagos20-calculator/006.xml')],
            /**
             * 1 Pago, 38 documentos relacionados con IVA0
             *
             * @see file://tests/assets/pagos20-calculator/007.xml
             */
            'pagos20-calculator/007.xml' => [self::utilAsset('pagos20-calculator/007.xml')],
            /**
             * 1 Pago, el monto (168168.00) es mayor al mínimo (168167.99), tiene IVA Retenido y Trasladado
             * El cálculo de la sumatoria de impuestos es sensible a truncado o redondeo
             *
             * @see file://tests/assets/pagos20-calculator/008.xml
             */
            'pagos20-calculator/008.xml' => [self::utilAsset('pagos20-calculator/008.xml')],
        ];
    }

    /** @dataProvider providerPredefinedCfdi */
    public function testPredefinedCfdi(string $cfdiFile): void
    {
        if (! file_exists($cfdiFile) || ! is_file($cfdiFile)) {
            throw new LogicException(sprintf('File %s does not exists', $cfdiFile));
        }

        $cfdiContents = (string) file_get_contents($cfdiFile);
        $cfdi = Cfdi::newFromString($cfdiContents);
        $nodePagos = $cfdi->getNode()->searchNode('cfdi:Complemento', 'pago20:Pagos');
        if (null === $nodePagos) {
            throw new LogicException(sprintf('File %s does not have a pago20:Pagos node', $cfdiFile));
        }

        $calculator = new Calculator();
        $pagos = $calculator->calculate($nodePagos);
        // echo PHP_EOL, json_encode($pagos, JSON_PRETTY_PRINT);

        // totales
        $nodeTotales = $nodePagos->searchNode('pago20:Totales');
        $totales = $pagos->getTotales();
        $this->assertSame($nodeTotales['MontoTotalPagos'], (string) $totales->getTotal());
        $this->checkAttributeDecimal($nodeTotales, 'TotalRetencionesIVA', $totales->getRetencionIva());
        $this->checkAttributeDecimal($nodeTotales, 'TotalRetencionesISR', $totales->getRetencionIsr());
        $this->checkAttributeDecimal($nodeTotales, 'TotalRetencionesIEPS', $totales->getRetencionIeps());
        $this->checkAttributeDecimal($nodeTotales, 'TotalTrasladosBaseIVA16', $totales->getTrasladoIva16Base());
        $this->checkAttributeDecimal($nodeTotales, 'TotalTrasladosImpuestoIVA16', $totales->getTrasladoIva16Importe());
        $this->checkAttributeDecimal($nodeTotales, 'TotalTrasladosBaseIVA8', $totales->getTrasladoIva08Base());
        $this->checkAttributeDecimal($nodeTotales, 'TotalTrasladosImpuestoIVA8', $totales->getTrasladoIva08Importe());
        $this->checkAttributeDecimal($nodeTotales, 'TotalTrasladosBaseIVA0', $totales->getTrasladoIva00Base());
        $this->checkAttributeDecimal($nodeTotales, 'TotalTrasladosImpuestoIVA0', $totales->getTrasladoIva00Importe());
        $this->checkAttributeDecimal($nodeTotales, 'TotalTrasladosBaseIVAExento', $totales->getTrasladoIvaExento());

        $processedPagos = [];
        // pago@monto, pago/impuestos
        foreach ($nodePagos->searchNodes('pago20:Pago') as $index => $nodePago) {
            $pago = $pagos->getPago($index);
            $processedPagos[] = $pago;
            $this->assertTrue($pago->getMontoMinimo()->compareTo(new Decimal($nodePago['Monto'])) <= 0);
            $nodeRetenciones = $nodePago->searchNodes('pago20:ImpuestosP', 'pago20:RetencionesP', 'pago20:RetencionP');
            foreach ($nodeRetenciones as $nodeRetencion) {
                $retencion = $pago->getImpuestos()->getRetencion($nodeRetencion['ImpuestoP']);
                $this->checkDecimalEquals($retencion->getImporte(), new Decimal($nodeRetencion['ImporteP']));
            }
            $nodeTraslados = $nodePago->searchNodes('pago20:ImpuestosP', 'pago20:TrasladosP', 'pago20:TrasladoP');
            foreach ($nodeTraslados as $nodeTraslado) {
                $traslado = $pago->getImpuestos()->getTraslado(
                    $nodeTraslado['ImpuestoP'],
                    $nodeTraslado['TipoFactorP'],
                    $nodeTraslado['TasaOCuotaP']
                );
                $this->checkDecimalEquals($traslado->getBase(), new Decimal($nodeTraslado['BaseP']));
                $this->checkDecimalEquals($traslado->getImporte(), new Decimal($nodeTraslado['ImporteP']));
            }
        }

        // check the result does not have additional pago elements
        $missingPagos = array_filter(
            $pagos->getPagos(),
            function (Pago $pago) use ($processedPagos): bool {
                return ! in_array($pago, $processedPagos, true);
            }
        );
        $this->assertSame([], $missingPagos, 'The result contains pagos that has not been processed');
    }

    private function checkAttributeDecimal(NodeInterface $node, string $attribute, ?Decimal $value): void
    {
        if (! isset($node[$attribute])) {
            $this->assertNull($value, "Since attribute $attribute does not exists, then value must be null");
        } else {
            $this->checkDecimalEquals(
                $value,
                new Decimal($node[$attribute]),
                "Attribute {$node->name()}@$attribute does not match with value"
            );
        }
    }

    private function checkDecimalEquals(Decimal $expected, Decimal $value, string $message = ''): void
    {
        $this->assertTrue(
            0 === $expected->compareTo($value),
            sprintf("%s\nExpected: %s Actual: %s", $message, $expected->getValue(), $value->getValue())
        );
    }
}
