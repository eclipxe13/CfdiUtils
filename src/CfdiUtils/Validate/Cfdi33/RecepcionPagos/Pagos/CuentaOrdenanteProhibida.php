<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Nodes\NodeInterface;

/**
 * PAGO13: En un pago, cuando la forma de pago no sea bancarizada la cuenta ordenante no debe existir (CRP212)
 */
class CuentaOrdenanteProhibida extends AbstractPagoValidator
{
    protected $code = 'PAGO13';

    protected $title = 'En un pago, cuando la forma de pago no sea bancarizada'
        . ' la cuenta ordenante no debe existir (CRP212)';

    public function validatePago(NodeInterface $pago): bool
    {
        $payment = $this->createPaymentType($pago['FormaDePagoP']);

        // si NO es banzarizado y está establecida la cuenta ordenante existe
        if (! $payment->allowSenderAccount() && $pago->offsetExists('CtaOrdenante')) {
            throw new ValidatePagoException(sprintf('Bancarizado: Sí, Cuenta: "%s"', $pago['CtaOrdenante']));
        }

        return true;
    }
}
