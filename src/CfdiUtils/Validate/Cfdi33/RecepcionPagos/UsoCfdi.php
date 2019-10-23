<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Cfdi33\Abstracts\AbstractRecepcionPagos10;
use CfdiUtils\Validate\Status;

/**
 * UsoCfdi
 *
 * - PAGUSO01: El uso del CFDI debe ser "P01" (CRP110)
 */
class UsoCfdi extends AbstractRecepcionPagos10
{
    public function validateRecepcionPagos(NodeInterface $comprobante, Asserts $asserts)
    {
        $assert = $asserts->put('PAGUSO01', 'El uso del CFDI debe ser "P01" (CRP110)');

        $receptor = $comprobante->searchNode('cfdi:Receptor');
        if (null === $receptor) {
            $assert->setStatus(Status::error(), 'No se encontró el nodo Receptor');
            return;
        }
        $assert->setStatus(
            Status::when('P01' === $receptor['UsoCFDI']),
            sprintf('Uso CFDI: "%s"', $receptor['UsoCFDI'])
        );
    }
}
