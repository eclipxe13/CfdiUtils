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
 * - CONCEPIMPC01: Si se utiliza el nodo impuestos en un concepto entonces se deben incluir traslados o retenciones
 */
class ConceptoImpuestos extends AbstractDiscoverableVersion33
{
    public function validate(NodeInterface $comprobante, Asserts $asserts)
    {
        $asserts->put(
            'CONCEPIMPC01',
            'Si se utiliza el nodo impuestos en un concepto entonces se deben incluir traslados y retenciones',
            Status::when($this->allConceptosImpuestosHasTrasladosOrRetenciones($comprobante))
        );
    }

    public function allConceptosImpuestosHasTrasladosOrRetenciones(NodeInterface $comprobante): bool
    {
        foreach ($comprobante->searchNodes('cfdi:Conceptos', 'cfdi:Concepto') as $concepto) {
            $impuestos = $concepto->searchNode('cfdi:Impuestos');
            if (null === $impuestos) {
                continue;
            }
            if ($impuestos->searchNodes('cfdi:Traslados', 'cfdi:Traslado')->count()
                || $impuestos->searchNodes('cfdi:Retenciones', 'cfdi:Retencion')->count()) {
                continue;
            }
            return false;
        }
        return true;
    }
}
