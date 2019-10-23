<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Utils\Rfc;

/**
 * PAGO15: En un pago, cuando el RFC del banco emisor de la cuenta beneficiaria existe
 *         debe ser válido y diferente de "XAXX010101000"
 */
class BancoBeneficiarioRfcCorrecto extends AbstractPagoValidator
{
    protected $code = 'PAGO15';

    protected $title = 'En un pago, cuando el RFC del banco emisor de la cuenta beneficiaria existe'
        . ' debe ser válido y diferente de "XAXX010101000"';

    public function validatePago(NodeInterface $pago): bool
    {
        if ($pago->offsetExists('RfcEmisorCtaBen')) {
            try {
                Rfc::checkIsValid($pago['RfcEmisorCtaBen'], Rfc::DISALLOW_GENERIC);
            } catch (\UnexpectedValueException $exception) {
                throw new ValidatePagoException($exception->getMessage());
            }
        }

        return true;
    }
}
