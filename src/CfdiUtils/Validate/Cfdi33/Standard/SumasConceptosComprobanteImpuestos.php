<?php

namespace CfdiUtils\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\SumasConceptos\SumasConceptos;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Cfdi33\Abstracts\AbstractDiscoverableVersion33;
use CfdiUtils\Validate\Status;

/**
 * SumasConceptosComprobanteImpuestos
 *
 * Esta clase genera la suma de subtotal, descuento, total e impuestos a partir de las sumas de los conceptos.
 * Con estas sumas valida contra los valores del comprobante, los valores de impuestos
 * y la lista de impuestos trasladados y retenidos
 *
 * Valida que:
 * - SUMAS01: La suma de los importes de conceptos es igual a el subtotal del comprobante
 * - SUMAS02: La suma de los descuentos es igual a el descuento del comprobante
 * - SUMAS03: El cálculo del total es igual a el total del comprobante
 *
 * - SUMAS04: El cálculo de impuestos trasladados es igual a el total de impuestos trasladados
 * - SUMAS05: Todos los impuestos trasladados existen en el comprobante
 * - SUMAS06: Todos los valores de los impuestos trasladados conciden con el comprobante
 * - SUMAS07: No existen más nodos de impuestos trasladados en el comprobante de los que se han calculado
 *
 * - SUMAS08: El cálculo de impuestos retenidos es igual a el total de impuestos retenidos
 * - SUMAS09: Todos los impuestos retenidos existen en el comprobante
 * - SUMAS10: Todos los valores de los impuestos retenidos conciden con el comprobante
 * - SUMAS11: No existen más nodos de impuestos trasladados en el comprobante de los que se han calculado
 *
 * - SUMAS12: El cálculo del descuento debe ser menor o igual al cálculo del subtotal
 *
 * - Adicionalmente, para SUMAS06 y SUMAS10 se generan: SUMASxx:yyy donde
 *      - xx puede ser 06 o 10
 *      - yyy es el consecutivo de la línea del impuesto
 *      - Se valida que El importe dek impuesto del Grupo X Impuesto X Tipo factor X Tasa o cuota X
 *                 es igual a el importe del nodo
 */
class SumasConceptosComprobanteImpuestos extends AbstractDiscoverableVersion33
{
    /** @var NodeInterface */
    private $comprobante;

    /** @var Asserts */
    private $asserts;

    /** @var \CfdiUtils\SumasConceptos\SumasConceptos */
    private $sumasConceptos;

    private function registerAsserts()
    {
        $asserts = [
            'SUMAS01' => 'La suma de los importes de conceptos es igual a el subtotal del comprobante',
            'SUMAS02' => 'La suma de los descuentos es igual a el descuento del comprobante',
            'SUMAS03' => 'El cálculo del total es igual a el total del comprobante',
            'SUMAS04' => 'El cálculo de impuestos trasladados es igual a el total de impuestos trasladados',
            'SUMAS05' => 'Todos los impuestos trasladados existen en el comprobante',
            'SUMAS06' => 'Todos los valores de los impuestos trasladados conciden con el comprobante',
            'SUMAS07' => 'No existen más nodos de impuestos trasladados en el comprobante de los que se han calculado',
            'SUMAS08' => 'El cálculo de impuestos retenidos es igual a el total de impuestos retenidos',
            'SUMAS09' => 'Todos los impuestos retenidos existen en el comprobante',
            'SUMAS10' => 'Todos los valores de los impuestos retenidos conciden con el comprobante',
            'SUMAS11' => 'No existen más nodos de impuestos trasladados en el comprobante de los que se han calculado',
            'SUMAS12' => 'El cálculo del descuento debe ser menor o igual al cálculo del subtotal',
        ];
        foreach ($asserts as $code => $title) {
            $this->asserts->put($code, $title);
        }
    }

    public function validate(NodeInterface $comprobante, Asserts $asserts)
    {
        $this->asserts = $asserts;
        $this->comprobante = $comprobante;
        $this->registerAsserts();

        $this->sumasConceptos = new SumasConceptos($comprobante);

        $this->validateSubTotal();
        $this->validateDescuento();
        $this->validateTotal();
        $this->validateImpuestosTrasladados();
        $this->validateTrasladosMatch();
        $this->validateImpuestosRetenidos();
        $this->validateRetencionesMatch();
        $this->validateDescuentoLessOrEqualThanSubTotal();
    }

    private function validateSubTotal()
    {
        $this->validateValues(
            'SUMAS01',
            'Calculado',
            $this->sumasConceptos->getSubTotal(),
            'Comprobante',
            (float) $this->comprobante['SubTotal']
        );
    }

    private function validateDescuento()
    {
        $this->validateValues(
            'SUMAS02',
            'Calculado',
            $this->sumasConceptos->getDescuento(),
            'Comprobante',
            (float) $this->comprobante['Descuento']
        );
    }

    private function validateDescuentoLessOrEqualThanSubTotal()
    {
        $subtotal = (float) $this->comprobante['SubTotal'];
        $descuento = (float) $this->comprobante['Descuento'];
        $this->asserts->putStatus(
            'SUMAS12',
            Status::when($subtotal >= $descuento),
            vsprintf('SubTotal: %s, Descuento: %s', [$this->comprobante['SubTotal'], $this->comprobante['Descuento']])
        );
    }

    private function validateTotal()
    {
        $this->validateValues(
            'SUMAS03',
            'Calculado',
            $this->sumasConceptos->getTotal(),
            'Comprobante',
            (float) $this->comprobante['Total']
        );
    }

    private function validateImpuestosTrasladados()
    {
        $this->validateValues(
            'SUMAS04',
            'Calculado',
            $this->sumasConceptos->getImpuestosTrasladados(),
            'Comprobante',
            (float) $this->comprobante->searchAttribute('cfdi:Impuestos', 'TotalImpuestosTrasladados')
        );
    }

    private function validateImpuestosRetenidos()
    {
        $this->validateValues(
            'SUMAS08',
            'Calculado',
            $this->sumasConceptos->getImpuestosRetenidos(),
            'Comprobante',
            (float) $this->comprobante->searchAttribute('cfdi:Impuestos', 'TotalImpuestosRetenidos')
        );
    }

    private function validateTrasladosMatch()
    {
        $this->validateImpuestosMatch(
            5,
            'traslado',
            $this->sumasConceptos->getTraslados(),
            ['cfdi:Impuestos', 'cfdi:Traslados', 'cfdi:Traslado'],
            ['Impuesto', 'TipoFactor', 'TasaOCuota']
        );
    }

    private function validateRetencionesMatch()
    {
        $this->validateImpuestosMatch(
            9,
            'retención',
            $this->sumasConceptos->getRetenciones(),
            ['cfdi:Impuestos', 'cfdi:Retenciones', 'cfdi:Retencion'],
            ['Impuesto']
        );
    }

    private function validateImpuestosMatch(
        int $assertOffset,
        string $type,
        array $expectedItems,
        array $impuestosPath,
        array $impuestosKeys
    ) {
        $extractedItems = [];
        foreach ($this->comprobante->searchNodes(...$impuestosPath) as $extracted) {
            $new = [];
            foreach ($impuestosKeys as $impuestosKey) {
                $new[$impuestosKey] = $extracted[$impuestosKey];
            }
            $new['Importe'] = $extracted['Importe'];
            $new['Encontrado'] = false;
            $newKey = SumasConceptos::impuestoKey(
                $extracted['Impuesto'],
                $extracted['TipoFactor'],
                $extracted['TasaOCuota']
            );
            $extractedItems[$newKey] = $new;
        }

        // check that all elements are found and mark extracted item as found
        $allExpectedAreFound = true;
        $allValuesMatch = true;
        $expectedOffset = 0;
        foreach ($expectedItems as $expectedKey => $expectedItem) {
            $expectedOffset = $expectedOffset + 1;
            if (! array_key_exists($expectedKey, $extractedItems)) {
                $allExpectedAreFound = false;
                $extractedItem = ['Importe' => ''];
            } else {
                // set found flag
                $extractedItems[$expectedKey]['Encontrado'] = true;
                // check value match
                $extractedItem = $extractedItems[$expectedKey];
            }
            $code = sprintf('SUMAS%02d:%03d', $assertOffset + 1, $expectedOffset);
            $thisValueMatch = $this->validateImpuestoImporte($type, $code, $expectedItem, $extractedItem);
            $allValuesMatch = $allValuesMatch && $thisValueMatch;
        }
        $extractedWithoutMatch = array_reduce($extractedItems, function (int $carry, array $item) {
            return $carry + (($item['Encontrado']) ? 0 : 1);
        }, 0);

        $this->asserts->putStatus(sprintf('SUMAS%02d', $assertOffset), Status::when($allExpectedAreFound));
        $this->asserts->putStatus(sprintf('SUMAS%02d', $assertOffset + 1), Status::when($allValuesMatch));
        $this->asserts->putStatus(
            sprintf('SUMAS%02d', $assertOffset + 2),
            Status::when(0 === $extractedWithoutMatch),
            sprintf('No encontrados: %d', $extractedWithoutMatch)
        );
    }

    private function validateImpuestoImporte(string $type, string $code, array $expected, array $extracted)
    {
        $label = sprintf('Grupo %s Impuesto %s', $type, $expected['Impuesto']);
        if (array_key_exists('TipoFactor', $expected)) {
            $label .= sprintf(' Tipo factor %s', $expected['TipoFactor']);
        }
        if (array_key_exists('TasaOCuota', $expected)) {
            $label .= sprintf(' Tasa o cuota %s', $expected['TasaOCuota']);
        }
        $this->asserts->put($code, sprintf('El importe del impuesto %s es igual a el importe del nodo', $label));
        return $this->validateValues(
            $code,
            'Calculado',
            (float) $expected['Importe'],
            'Encontrado',
            (float) $extracted['Importe']
        );
    }

    private function validateValues(
        string $code,
        string $expectedLabel,
        float $expectedValue,
        string $compareLabel,
        float $compareValue,
        Status $errorStatus = null
    ): bool {
        $condition = $this->compareImportesAreEqual($expectedValue, $compareValue);
        $this->asserts->putStatus(
            $code,
            Status::when($condition, $errorStatus),
            sprintf('%s: %s, %s: %s', $expectedLabel, $expectedValue, $compareLabel, $compareValue)
        );
        return $condition;
    }

    private function compareImportesAreEqual(float $first, float $second, float $delta = null): bool
    {
        if (null === $delta) {
            $delta = 0.000001;
        }
        return (abs($first - $second) <= $delta);
    }
}
