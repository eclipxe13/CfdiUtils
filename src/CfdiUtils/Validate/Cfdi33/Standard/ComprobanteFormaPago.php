<?php

namespace CfdiUtils\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Cfdi33\Abstracts\AbstractDiscoverableVersion33;
use CfdiUtils\Validate\Status;

/**
 * ComprobanteFormaPago
 *
 * Valida que:
 * - FORMAPAGO01: El campo forma de pago no debe existir cuando existe el complemento para recepción de pagos
 *                (CFDI33103)
 */
class ComprobanteFormaPago extends AbstractDiscoverableVersion33
{
    public function validate(NodeInterface $comprobante, Asserts $asserts)
    {
        $assert = $asserts->put(
            'FORMAPAGO01',
            'El campo forma de pago no debe existir cuando existe el complemento para recepción de pagos (CFDI33103)',
            Status::none()
        );

        $existsComplementoPagos = (null !== $comprobante->searchNode('cfdi:Complemento', 'pago10:Pagos'));
        if ($existsComplementoPagos) {
            $existsFormaPago = $comprobante->offsetExists('FormaPago');
            $assert->setStatus(Status::when(! $existsFormaPago));
        }
    }
}
