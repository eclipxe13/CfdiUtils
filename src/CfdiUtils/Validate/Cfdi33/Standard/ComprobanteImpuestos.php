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
 * - COMPIMPUESTOSC01: Si existe el nodo impuestos entonces debe incluir el total de traslados
 *                     y/o el total de retenciones
 * - COMPIMPUESTOSC02: Si existe al menos un traslado entonces debe existir el total de traslados
 * - COMPIMPUESTOSC03: Si existe al menos una retención entonces debe existir el total de retenciones
 */
class ComprobanteImpuestos extends AbstractDiscoverableVersion33
{
    private function registerAsserts(Asserts $asserts)
    {
        $assertDescriptions = [
            'COMPIMPUESTOSC01' => 'Si existe el nodo impuestos entonces debe incluir el total detraslados y/o'
                . ' el total de retenciones',
            'COMPIMPUESTOSC02' => 'Si existe al menos un traslado entonces debe existir el total de traslados',
            'COMPIMPUESTOSC03' => 'Si existe al menos una retención entonces debe existir el total de retenciones',
        ];
        foreach ($assertDescriptions as $code => $title) {
            $asserts->put($code, $title);
        }
    }

    public function validate(NodeInterface $comprobante, Asserts $asserts)
    {
        $this->registerAsserts($asserts);

        $nodeImpuestos = $comprobante->searchNode('cfdi:Impuestos');
        if (null === $nodeImpuestos) {
            return;
        }

        $existsTotalTrasladados = $nodeImpuestos->offsetExists('TotalImpuestosTrasladados');
        $existsTotalRetenidos = $nodeImpuestos->offsetExists('TotalImpuestosRetenidos');

        $asserts->putStatus(
            'COMPIMPUESTOSC01',
            Status::when($existsTotalTrasladados || $existsTotalRetenidos)
        );

        $hasTraslados = (null !== $nodeImpuestos->searchNode('cfdi:Traslados', 'cfdi:Traslado'));
        $asserts->putStatus(
            'COMPIMPUESTOSC02',
            Status::when(! ($hasTraslados && ! $existsTotalTrasladados))
        );

        $hasRetenciones = (null !== $nodeImpuestos->searchNode('cfdi:Retenciones', 'cfdi:Retencion'));
        $asserts->putStatus(
            'COMPIMPUESTOSC03',
            Status::when(! ($hasRetenciones && ! $existsTotalRetenidos))
        );
    }
}
