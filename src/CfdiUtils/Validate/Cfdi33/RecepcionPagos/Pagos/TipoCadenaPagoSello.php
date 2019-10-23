<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Nodes\NodeInterface;

/**
 * PAGO22: En un pago, si existe el tipo de cadena de pago debe existir
 *         el sello del pago  y viceversa (CRP231 y CRP232)
 */
class TipoCadenaPagoSello extends AbstractPagoValidator
{
    protected $code = 'PAGO22';

    protected $title = 'En un pago, si existe el tipo de cadena de pago debe existir'
        . ' el sello del pago  y viceversa (CRP231 y CRP232)';

    public function validatePago(NodeInterface $pago): bool
    {
        if ((('' !== $pago['TipoCadPago']) xor ('' !== $pago['SelloPago']))
            || ($pago->offsetExists('TipoCadPago') xor $pago->offsetExists('SelloPago'))) {
            throw new ValidatePagoException(
                sprintf('Tipo cadena pago: "%s", Sello: "%s"', $pago['TipoCadPago'], $pago['SelloPago'])
            );
        }

        return true;
    }
}
