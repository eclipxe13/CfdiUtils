<?php

namespace CfdiUtils\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Cfdi33\Abstracts\AbstractDiscoverableVersion33;
use CfdiUtils\Validate\Status;

/**
 * ConceptoImpuestos
 *
 * Valida que:
 * - CONCEPIMPC01: El nodo impuestos de un concepto debe incluir traslados y/o retenciones (CFDI33152)
 * - CONCEPIMPC02: Los traslados de los impuestos de un concepto deben tener una base y ser mayor a cero (CFDI33154)
 * - CONCEPIMPC03: No se debe registrar la tasa o cuota ni el importe cuando
 *                 el tipo de factor de traslado es exento (CFDI33157)
 * - CONCEPIMPC04: Se debe registrar la tasa o cuota y el importe cuando
 *                 el tipo de factor de traslado es tasa o cuota (CFDI33158)
 * - CONCEPIMPC05: Las retenciones de los impuestos de un concepto deben tener una base y ser mayor a cero (CFDI33154)
 * - CONCEPIMPC06: Las retenciones de los impuestos de un concepto deben tener
 *                 un tipo de factor diferente de exento (CFDI33166)
 */
class ConceptoImpuestos extends AbstractDiscoverableVersion33
{
    private function registerAsserts(Asserts $asserts)
    {
        $assertDescriptions = [
            'CONCEPIMPC01' => 'El nodo impuestos de un concepto debe incluir traslados y/o retenciones (CFDI33152)',
            'CONCEPIMPC02' => 'Los traslados de los impuestos de un concepto deben tener una base y ser mayor a cero'
                . ' (CFDI33154)',
            'CONCEPIMPC03' => 'No se debe registrar la tasa o cuota ni el importe cuando el tipo de factor de traslado'
                . ' es exento (CFDI33157)',
            'CONCEPIMPC04' => 'Se debe registrar la tasa o cuota y el importe cuando el tipo de factor de traslado'
                . ' es tasa o cuota (CFDI33158)',
            'CONCEPIMPC05' => 'Las retenciones de los impuestos de un concepto deben tener una base y ser mayor a cero'
                . '(CFDI33154)',
            'CONCEPIMPC06' => ' Las retenciones de los impuestos de un concepto deben tener un tipo de factor diferente'
                . ' de exento (CFDI33166)',
        ];
        foreach ($assertDescriptions as $code => $title) {
            $asserts->put($code, $title);
        }
    }

    public function validate(NodeInterface $comprobante, Asserts $asserts)
    {
        $this->registerAsserts($asserts);

        $status01 = Status::ok();
        $status02 = Status::ok();
        $status03 = Status::ok();
        $status04 = Status::ok();
        $status05 = Status::ok();
        $status06 = Status::ok();

        foreach ($comprobante->searchNodes('cfdi:Conceptos', 'cfdi:Concepto') as $i => $concepto) {
            if ($status01->isOk() && ! $this->conceptoImpuestosHasTrasladosOrRetenciones($concepto)) {
                $status01 = Status::error();
                $asserts->get('CONCEPIMPC01')->setExplanation(sprintf('Concepto #%d', $i));
            }

            $traslados = $concepto->searchNodes('cfdi:Impuestos', 'cfdi:Traslados', 'cfdi:Traslado');
            foreach ($traslados as $k => $traslado) {
                if ($status02->isOk() && ! $this->impuestoHasBaseGreaterThanZero($traslado)) {
                    $status02 = Status::error();
                    $asserts->get('CONCEPIMPC02')->setExplanation(sprintf('Concepto #%d, Traslado #%d', $i, $k));
                }
                if ($status03->isOk() && ! $this->trasladoHasTipoFactorExento($traslado)) {
                    $status03 = Status::error();
                    $asserts->get('CONCEPIMPC03')->setExplanation(sprintf('Concepto #%d, Traslado #%d', $i, $k));
                }
                if ($status04->isOk() && ! $this->trasladoHasTipoFactorTasaOCuota($traslado)) {
                    $status04 = Status::error();
                    $asserts->get('CONCEPIMPC04')->setExplanation(sprintf('Concepto #%d, Traslado #%d', $i, $k));
                }
            }

            $retenciones = $concepto->searchNodes('cfdi:Impuestos', 'cfdi:Retenciones', 'cfdi:Retencion');
            foreach ($retenciones as $k => $retencion) {
                if ($status05->isOk() && ! $this->impuestoHasBaseGreaterThanZero($retencion)) {
                    $status05 = Status::error();
                    $asserts->get('CONCEPIMPC05')->setExplanation(sprintf('Concepto #%d, Retención #%d', $i, $k));
                }
                if ($status06->isOk() && 'Exento' === $retencion['TipoFactor']) {
                    $status06 = Status::error();
                    $asserts->get('CONCEPIMPC06')->setExplanation(sprintf('Concepto #%d, Retención #%d', $i, $k));
                }
            }
        }

        $asserts->putStatus('CONCEPIMPC01', $status01);
        $asserts->putStatus('CONCEPIMPC02', $status02);
        $asserts->putStatus('CONCEPIMPC03', $status03);
        $asserts->putStatus('CONCEPIMPC04', $status04);
        $asserts->putStatus('CONCEPIMPC05', $status05);
        $asserts->putStatus('CONCEPIMPC06', $status06);
    }

    private function conceptoImpuestosHasTrasladosOrRetenciones(NodeInterface $concepto): bool
    {
        $impuestos = $concepto->searchNode('cfdi:Impuestos');
        if (null === $impuestos) {
            return true;
        }
        if ($impuestos->searchNodes('cfdi:Traslados', 'cfdi:Traslado')->count()
            || $impuestos->searchNodes('cfdi:Retenciones', 'cfdi:Retencion')->count()) {
            return true;
        }
        return false;
    }

    private function impuestoHasBaseGreaterThanZero(NodeInterface $impuesto): bool
    {
        if (! $impuesto->offsetExists('Base')) {
            return false;
        }
        if (! is_numeric($impuesto['Base'])) {
            return false;
        }
        if ((float) $impuesto['Base'] < 0.000001) {
            return false;
        }
        return true;
    }

    private function trasladoHasTipoFactorExento(NodeInterface $traslado): bool
    {
        if ('Exento' === $traslado['TipoFactor']) {
            if ($traslado->offsetExists('TasaOCuota')) {
                return false;
            }
            if ($traslado->offsetExists('Importe')) {
                return false;
            }
        }
        return true;
    }

    private function trasladoHasTipoFactorTasaOCuota(NodeInterface $traslado): bool
    {
        if ('Tasa' === $traslado['TipoFactor'] || 'Cuota' === $traslado['TipoFactor']) {
            if ('' === $traslado['TasaOCuota']) {
                return false;
            }
            if ('' === $traslado['Importe']) {
                return false;
            }
        }
        return true;
    }
}
