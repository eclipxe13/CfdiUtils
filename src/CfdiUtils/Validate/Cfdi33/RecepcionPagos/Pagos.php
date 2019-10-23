<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Cfdi33\Abstracts\AbstractRecepcionPagos10;
use CfdiUtils\Validate\Status;

/**
 * Pagos - Valida el contenido del nodo del complemento de pago
 *
 * - PAGOS01: Debe existir al menos un pago en el complemento de pagos
 */
class Pagos extends AbstractRecepcionPagos10
{
    public function validateRecepcionPagos(NodeInterface $comprobante, Asserts $asserts)
    {
        $assert = $asserts->put('PAGOS01', 'Debe existir al menos un pago en el complemento de pagos');

        $pagoCollection = $comprobante->searchNodes('cfdi:Complemento', 'pago10:Pagos', 'pago10:Pago');
        $assert->setStatus(
            Status::when($pagoCollection->count() > 0),
            'Debe existir al menos un pago en el complemento de pagos'
        );
    }
}
