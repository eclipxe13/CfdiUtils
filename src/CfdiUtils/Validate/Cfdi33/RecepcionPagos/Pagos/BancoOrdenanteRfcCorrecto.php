<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Utils\Rfc;

/**
 * PAGO10: En un pago, cuando el RFC del banco emisor de la cuenta ordenante existe
 *         debe ser válido y diferente de "XAXX010101000"
 */
class BancoOrdenanteRfcCorrecto extends AbstractPagoValidator
{
    protected $code = 'PAGO10';

    protected $title = 'En un pago, cuando el RFC del banco emisor de la cuenta ordenante existe'
        . ' debe ser válido y diferente de "XAXX010101000"';

    public function validatePago(NodeInterface $pago): bool
    {
        if ($pago->offsetExists('RfcEmisorCtaOrd')) {
            try {
                Rfc::checkIsValid($pago['RfcEmisorCtaOrd'], Rfc::DISALLOW_GENERIC);
            } catch (\UnexpectedValueException $exception) {
                throw new ValidatePagoException($exception->getMessage());
            }
        }

        return true;
    }
}
