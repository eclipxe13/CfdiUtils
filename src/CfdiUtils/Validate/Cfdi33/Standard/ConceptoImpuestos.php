<?php
namespace CfdiUtils\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Cfdi33\Abstracts\AbstractDiscoverableVersion33;
use CfdiUtils\Validate\Status;

/**
 * ConceptoImpuestos.php
 *
 * Valida que:
 * - CONCEPIMPC01: El nodo impuestos de un concepto debe incluir traslados y/o retenciones (CFDI33152)
 * - CONCEPIMPC02: Los traslados de los impuestos de un concepto deben tener una base y ser mayor a cero (CFDI33154)
 */
class ConceptoImpuestos extends AbstractDiscoverableVersion33
{
    public function validate(NodeInterface $comprobante, Asserts $asserts)
    {
        $asserts->put(
            'CONCEPIMPC01',
            'El nodo impuestos de un concepto debe incluir traslados y/o retenciones (CFDI33152)'
        );
        $asserts->put(
            'CONCEPIMPC02',
            'Los traslados de los impuestos de un concepto deben tener una base y ser mayor a cero (CFDI33154)'
        );

        $allConceptoImpuestosHasTrasladosOrRetenciones = true;
        $allTrasladosHasBaseGreaterThanZero = true;

        foreach ($comprobante->searchNodes('cfdi:Conceptos', 'cfdi:Concepto') as $concepto) {
            if (! $this->conceptoImpuestosHasTrasladosOrRetenciones($concepto)) {
                $allConceptoImpuestosHasTrasladosOrRetenciones = false;
            }
            foreach ($concepto->searchNodes('cfdi:Impuestos', 'cfdi:Traslados', 'cfdi:Traslado') as $traslado) {
                if (! $this->trasladoHasBaseGreaterThanZero($traslado)) {
                    $allTrasladosHasBaseGreaterThanZero = false;
                }
            }
        }

        $asserts->putStatus('CONCEPIMPC01', Status::when($allConceptoImpuestosHasTrasladosOrRetenciones));
        $asserts->putStatus('CONCEPIMPC02', Status::when($allTrasladosHasBaseGreaterThanZero));
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

    private function trasladoHasBaseGreaterThanZero(NodeInterface $traslado): bool
    {
        if (! isset($traslado['Base'])) {
            return $allTrasladosHasBaseGreaterThanZero = false;
        }
        if (! is_numeric($traslado['Base'])) {
            return false;
        }
        if ((float) $traslado['Base'] < 0.000001) {
            return false;
        }
        return true;
    }
}
