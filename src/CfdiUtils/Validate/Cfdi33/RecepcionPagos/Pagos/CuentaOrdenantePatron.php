<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Nodes\NodeInterface;

/**
 * PAGO14: En un pago, cuando la cuenta ordenante existe debe cumplir con su patrón específico (CRP213)
 */
class CuentaOrdenantePatron extends AbstractPagoValidator
{
    protected string $code = 'PAGO14';

    protected string $title = 'En un pago, cuando la cuenta ordenante existe debe cumplir con'
        . ' su patrón específico (CRP213)';

    public function validatePago(NodeInterface $pago): bool
    {
        // Solo validar si está establecida la cuenta ordenante
        if ($pago->exists('CtaOrdenante')) {
            $payment = $this->createPaymentType($pago['FormaDePagoP']);
            $pattern = $payment->senderAccountPattern();
            if (! preg_match($pattern, $pago['CtaOrdenante'])) {
                throw new ValidatePagoException(sprintf('Cuenta: "%s". Patrón "%s"', $pago['CtaOrdenante'], $pattern));
            }
        }

        return true;
    }
}
