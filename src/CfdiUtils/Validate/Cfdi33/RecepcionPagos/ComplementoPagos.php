<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos;

use CfdiUtils\Nodes\Node;
use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Cfdi33\Abstracts\AbstractDiscoverableVersion33;
use CfdiUtils\Validate\Status;

/**
 * ComplementoPagos
 *
 * Este complemento se ejecuta siempre
 *
 * - COMPPAG01: El complemento de pagos debe existir si el tipo de comprobante es P y viceversa
 * - COMPPAG02: Si el complemento de pagos existe su version debe ser 1.0
 * - COMPPAG03: Si el tipo de comprobante es P su versión debe ser 3.3
 * - COMPPAG04: No debe existir el nodo impuestos del complemento de pagos (CRP237)
 */
class ComplementoPagos extends AbstractDiscoverableVersion33
{
    public function validate(NodeInterface $comprobante, Asserts $asserts)
    {
        $asserts->put('COMPPAG01', 'El complemento de pagos debe existir si el tipo de comprobante es P y viceversa');
        $asserts->put('COMPPAG02', 'Si el complemento de pagos existe su version debe ser 1.0');
        $asserts->put('COMPPAG03', 'Si el tipo de comprobante es P su versión debe ser 3.3');
        $asserts->put('COMPPAG04', 'No debe existir el nodo impuestos del complemento de pagos (CRP237)');

        $pagosExists = true;
        $pagos10 = $comprobante->searchNode('cfdi:Complemento', 'pago10:Pagos');
        if (null === $pagos10) {
            $pagosExists = false;
            $pagos10 = new Node('pago10:Pagos'); // avoid accessing a null object
        }

        $isTipoPago = ('P' === $comprobante['TipoDeComprobante']);

        $asserts->putStatus(
            'COMPPAG01',
            Status::when(! ($isTipoPago xor $pagosExists)),
            sprintf(
                'TipoDeComprobante: "%s", Complemento: %s',
                $comprobante['TipoDeComprobante'],
                $pagosExists ? 'existe' : 'no existe'
            )
        );

        if ($pagosExists) {
            $asserts->putStatus('COMPPAG02', Status::when('1.0' === $pagos10['Version']));
        }
        if ($isTipoPago) {
            $asserts->putStatus('COMPPAG03', Status::when('3.3' === $comprobante['Version']));
        }

        $asserts->putStatus(
            'COMPPAG04',
            Status::when(null === $pagos10->searchNode('pago10:Impuestos'))
        );
    }
}
