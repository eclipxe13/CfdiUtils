<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Nodes\NodeInterface;

/**
 * PAGO18: En un pago, cuando la cuenta beneficiaria existe debe cumplir con su patrón específico (CRP239)
 */
class CuentaBeneficiariaPatron extends AbstractPagoValidator
{
    protected string $code = 'PAGO18';

    protected string $title = 'En un pago, cuando la cuenta beneficiaria existe'
        . ' debe cumplir con su patrón específico (CRP213)';

    public function validatePago(NodeInterface $pago): bool
    {
        // Solo validar si está establecida la cuenta ordenante
        if ($pago->exists('CtaBeneficiario')) {
            $payment = $this->createPaymentType($pago['FormaDePagoP']);
            $pattern = $payment->receiverAccountPattern();
            if (! preg_match($pattern, $pago['CtaBeneficiario'])) {
                throw new ValidatePagoException(sprintf('Cuenta: "%s". Patrón "%s"', $pago['CtaOrdenante'], $pattern));
            }
        }

        return true;
    }
}
