<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Nodes\NodeInterface;

/**
 * PAGO07: En un pago, el monto debe ser mayor a cero (CRP207)
 */
class MontoGreaterThanZero extends AbstractPagoValidator
{
    protected $code = 'PAGO07';

    protected $title = 'En un pago, el monto debe ser mayor a cero (CRP207)';

    public function validatePago(NodeInterface $pago): bool
    {
        if (! $this->isGreaterThan((float) $pago['Monto'], 0)) {
            throw new ValidatePagoException(sprintf('Monto: "%s"', $pago['Monto']));
        }

        return true;
    }
}
