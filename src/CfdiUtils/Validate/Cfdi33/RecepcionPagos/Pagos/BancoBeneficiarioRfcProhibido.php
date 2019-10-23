<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Nodes\NodeInterface;

/**
 * PAGO16: En un pago, cuando la forma de pago no sea 02, 03, 04, 05, 28, 29 o 99
 *         el RFC del banco de la cuenta beneficiaria no debe existir (CRP214)
 */
class BancoBeneficiarioRfcProhibido extends AbstractPagoValidator
{
    protected $code = 'PAGO16';

    protected $title = 'En un pago, cuando la forma de pago no sea 02, 03, 04, 05, 28, 29 o 99'
        . ' el RFC del banco de la cuenta beneficiaria no debe existir (CRP214)';

    public function validatePago(NodeInterface $pago): bool
    {
        $payment = $this->createPaymentType($pago['FormaDePagoP']);

        if (! $payment->allowReceiverRfc() && $pago->offsetExists('RfcEmisorCtaBen')) {
            throw new ValidatePagoException(
                sprintf('FormaDePago: "%s", Rfc: "%s"', $pago['FormaDePagoP'], $pago['RfcEmisorCtaBen'])
            );
        }

        return true;
    }
}
