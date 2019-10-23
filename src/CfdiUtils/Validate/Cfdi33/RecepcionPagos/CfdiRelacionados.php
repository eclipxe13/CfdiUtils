<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Cfdi33\Abstracts\AbstractRecepcionPagos10;
use CfdiUtils\Validate\Status;

/**
 * CfdiRelacionados
 *
 * - PAGREL01: El tipo de relación en los CFDI relacionados debe ser "04"
 */
class CfdiRelacionados extends AbstractRecepcionPagos10
{
    public function validateRecepcionPagos(NodeInterface $comprobante, Asserts $asserts)
    {
        $assert = $asserts->put('PAGREL01', 'El tipo de relación en los CFDI relacionados debe ser "04"');
        $cfdiRelacionados = $comprobante->searchNode('cfdi:CfdiRelacionados');
        if (null === $cfdiRelacionados) {
            return;
        }
        $assert->setStatus(
            Status::when('04' === $cfdiRelacionados['TipoRelacion']),
            sprintf('Tipo de relación: "%s"', $cfdiRelacionados['TipoRelacion'])
        );
    }
}
