<?php

namespace CfdiUtils\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Cfdi33\Abstracts\AbstractDiscoverableVersion33;
use CfdiUtils\Validate\Status;

/**
 * ConceptoDescuento
 *
 * Valida que:
 * - CONCEPDESC01: Si existe el atributo descuento en el concepto,
 *                 entonces debe ser menor o igual que el importe y mayor o igual que cero (CFDI33151)
 */
class ConceptoDescuento extends AbstractDiscoverableVersion33
{
    public function validate(NodeInterface $comprobante, Asserts $asserts)
    {
        $asserts->put(
            'CONCEPDESC01',
            'Si existe el atributo descuento en el concepto,'
              . ' entonces debe ser menor o igual que el importe y mayor o igual que cero (CFDI33151)'
        );
        $checked = 0;
        foreach ($comprobante->searchNodes('cfdi:Conceptos', 'cfdi:Concepto') as $i => $concepto) {
            $checked = $checked + 1;
            if ($this->conceptoHasInvalidDiscount($concepto)) {
                $explanation = sprintf(
                    'Concepto #%d, Descuento: "%s", Importe: "%s"',
                    $i,
                    $concepto['Descuento'],
                    $concepto['Importe']
                );
                $asserts->putStatus('CONCEPDESC01', Status::error(), $explanation);
            }
        }
        if ($checked > 0 && $asserts->get('CONCEPDESC01')->getStatus()->isNone()) {
            $asserts->putStatus('CONCEPDESC01', Status::ok(), sprintf('Revisados %d conceptos', $checked));
        }
    }

    public function conceptoHasInvalidDiscount(NodeInterface $concepto): bool
    {
        if (! $concepto->offsetExists('Descuento')) {
            return false;
        }
        $descuento = (float) $concepto['Descuento'];
        $importe = (float) $concepto['Importe'];
        return (! ($descuento >= 0 && $descuento <= $importe));
    }
}
