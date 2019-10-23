<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Nodes\NodeInterface;

/**
 * PAGO08: En un pago, el monto debe tener hasta la cantidad de decimales que soporte la moneda (CRP208)
 */
class MontoDecimals extends AbstractPagoValidator
{
    protected $code = 'PAGO08';

    protected $title = 'En un pago, el monto debe tener hasta la cantidad de decimales que soporte la moneda (CRP208)';

    public function validatePago(NodeInterface $pago): bool
    {
        $currency = $this->createCurrencyDecimals($pago['MonedaP']);
        if (! $currency->doesNotExceedDecimals($pago['Monto'])) {
            throw new ValidatePagoException(
                sprintf('Monto: "%s", MaxDecimals: %s', $pago['Monto'], $currency->decimals())
            );
        }

        return true;
    }
}
