<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Nodes\NodeInterface;

/**
 * PAGO12: En un pago, cuando la forma de pago no sea bancarizada el RFC del banco emisor no debe existir (CRP238)
 */
class BancoOrdenanteRfcProhibido extends AbstractPagoValidator
{
    protected $code = 'PAGO12';

    protected $title = 'En un pago, cuando la forma de pago no sea bancarizada'
        . ' el RFC del banco emisor no debe existir (CRP238)';

    public function validatePago(NodeInterface $pago): bool
    {
        if ('' === $pago['FormaDePagoP']) {
            throw new ValidatePagoException('No está establecida la forma de pago');
        }
        $payment = $this->createPaymentType($pago['FormaDePagoP']);

        // si NO es banzarizado y está establecido el RFC del Emisor de la cuenta ordenante
        if (! $payment->allowSenderRfc() && $pago->offsetExists('RfcEmisorCtaOrd')) {
            throw new ValidatePagoException(sprintf('Bancarizado: Sí, Rfc: "%s"', $pago['RfcEmisorCtaOrd']));
        }

        return true;
    }
}
