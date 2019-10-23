<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Utils\CurrencyDecimals;

/**
 * PAGO06: En un pago, el tipo de cambio debe ser numérico, no debe exceder 6 decimales y debe ser mayor a "0.000001"
 */
class TipoCambioValue extends AbstractPagoValidator
{
    protected $code = 'PAGO06';

    protected $title = 'En un pago, el tipo de cambio debe ser numérico,'
        . ' no debe exceder 6 decimales y debe ser mayor a "0.000001"';

    public function validatePago(NodeInterface $pago): bool
    {
        if (! $pago->offsetExists('TipoCambioP')) {
            return true;
        }
        $reason = '';
        if (! is_numeric($pago['TipoCambioP'])) {
            $reason = 'No es numérico';
        } elseif (CurrencyDecimals::decimalsCount((string) $pago['TipoCambioP']) > 6) {
            $reason = 'Tiene más de 6 decimales';
        } elseif (! $this->isGreaterThan((float) $pago['TipoCambioP'], 0.000001)) {
            $reason = 'No es mayor a "0.000001"';
        }
        if ('' !== $reason) {
            throw new ValidatePagoException(sprintf('TipoCambioP: "%s", %s', $pago['TipoCambioP'], $reason));
        }

        return true;
    }
}
