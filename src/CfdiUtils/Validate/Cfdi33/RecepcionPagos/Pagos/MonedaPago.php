<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Nodes\NodeInterface;

/**
 * PAGO04: En un pago, la moneda debe existir y no puede ser "XXX" (CRP202)
 */
class MonedaPago extends AbstractPagoValidator
{
    protected $code = 'PAGO04';

    protected $title = 'En un pago, la moneda debe existir y no puede ser "XXX" (CRP202)';

    public function validatePago(NodeInterface $pago): bool
    {
        if ('' === $pago['MonedaP'] || 'XXX' === $pago['MonedaP']) {
            throw new ValidatePagoException(sprintf('Moneda: "%s"', $pago['MonedaP']));
        }

        return true;
    }
}
