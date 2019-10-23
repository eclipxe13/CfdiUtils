<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Nodes\NodeInterface;

/**
 * PAGO05: En un pago, cuando la moneda no es "MXN" no debe existir tipo de cambio,
 *         de lo contrario el tipo de cambio debe existir (CRP203, CRP204)
 */
class TipoCambioExists extends AbstractPagoValidator
{
    protected $code = 'PAGO05';

    protected $title = 'En un pago, cuando la moneda no es "MXN" no debe existir tipo de cambio,'
        . ' de lo contrario el tipo de cambio debe existir (CRP203, CRP204)';

    public function validatePago(NodeInterface $pago): bool
    {
        if (! (('MXN' === $pago['MonedaP']) xor ('' !== $pago['TipoCambioP']))) {
            throw new ValidatePagoException(
                sprintf('Moneda: "%s", Tipo de cambio: "%s"', $pago['MonedaP'], $pago['TipoCambioP'])
            );
        }

        return true;
    }
}
