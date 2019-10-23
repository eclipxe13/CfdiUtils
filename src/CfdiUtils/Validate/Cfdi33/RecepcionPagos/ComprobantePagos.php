<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Cfdi33\Abstracts\AbstractRecepcionPagos10;
use CfdiUtils\Validate\Status;

/**
 * ComprobantePagos - Valida los datos relacionados al nodo Comprobante cuando es un CFDI de recepción de pagos
 *
 * - PAGCOMP01: Debe existir un solo nodo que represente el complemento de pagos
 * - PAGCOMP02: La forma de pago no debe existir (CRP104)
 * - PAGCOMP03: Las condiciones de pago no deben existir (CRP106)
 * - PAGCOMP04: El método de pago no deben existir (CRP105)
 * - PAGCOMP05: La moneda debe ser "XXX" (CRP103)
 * - PAGCOMP06: El tipo de cambio no debe existir (CRP108)
 * - PAGCOMP07: El descuento no debe existir (CRP107)
 * - PAGCOMP08: El subtotal del documento debe ser cero "0" (CRP102)
 * - PAGCOMP09: El total del documento debe ser cero "0" (CRP109)
 * - PAGCOMP10: No se debe registrar el apartado de Impuestos en el CFDI (CRP122)
 */
class ComprobantePagos extends AbstractRecepcionPagos10
{
    public function validateRecepcionPagos(NodeInterface $comprobante, Asserts $asserts)
    {
        $pagos = $comprobante->searchNodes('cfdi:Complemento', 'pago10:Pagos');
        $asserts->put(
            'PAGCOMP01',
            'Debe existir un solo nodo que represente el complemento de pagos',
            Status::when(1 === $pagos->count()),
            sprintf('Encontrados: %d', $pagos->count())
        );
        $asserts->put(
            'PAGCOMP02',
            'La forma de pago no debe existir (CRP104)',
            Status::when(! $comprobante->offsetExists('FormaPago'))
        );
        $asserts->put(
            'PAGCOMP03',
            'Las condiciones de pago no deben existir (CRP106)',
            Status::when(! $comprobante->offsetExists('CondicionesDePago'))
        );
        $asserts->put(
            'PAGCOMP04',
            'El método de pago no deben existir (CRP105)',
            Status::when(! $comprobante->offsetExists('MetodoPago'))
        );
        $asserts->put(
            'PAGCOMP05',
            'La moneda debe ser "XXX" (CRP103)',
            Status::when('XXX' === $comprobante['Moneda']),
            sprintf('Moneda: "%s"', $comprobante['Moneda'])
        );
        $asserts->put(
            'PAGCOMP06',
            'El tipo de cambio no debe existir (CRP108)',
            Status::when(! $comprobante->offsetExists('TipoCambio'))
        );
        $asserts->put(
            'PAGCOMP07',
            'El descuento no debe existir (CRP107)',
            Status::when(! $comprobante->offsetExists('Descuento'))
        );
        $asserts->put(
            'PAGCOMP08',
            'El subtotal del documento debe ser cero "0" (CRP102)',
            Status::when('0' === $comprobante['SubTotal']),
            sprintf('SubTotal: "%s"', $comprobante['SubTotal'])
        );
        $asserts->put(
            'PAGCOMP09',
            'El total del documento debe ser cero "0" (CRP109)',
            Status::when('0' === $comprobante['Total']),
            sprintf('Total: "%s"', $comprobante['Total'])
        );
        $asserts->put(
            'PAGCOMP10',
            'No se debe registrar el apartado de Impuestos en el CFDI (CRP122)',
            Status::when(0 === $comprobante->searchNodes('cfdi:Impuestos')->count())
        );
    }
}
