<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Nodes\NodeInterface;

/**
 * PAGO17: En un pago, cuando la forma de pago no sea 02, 03, 04, 05, 28, 29 o 99
 *         la cuenta beneficiaria no debe existir (CRP215)
 */
class CuentaBeneficiariaProhibida extends AbstractPagoValidator
{
    protected string $code = 'PAGO17';

    protected string $title = 'En un pago, cuando la forma de pago no sea 02, 03, 04, 05, 28, 29 o 99'
        . ' la cuenta beneficiaria no debe existir (CRP215)';

    public function validatePago(NodeInterface $pago): bool
    {
        $payment = $this->createPaymentType($pago['FormaDePagoP']);

        // si NO es banzarizado y está establecida la cuenta beneficiaria
        if (! $payment->allowReceiverAccount() && $pago->exists('CtaBeneficiario')) {
            throw new ValidatePagoException(
                sprintf('Forma de pago: "%s", Cuenta: "%s"', $pago['FormaDePagoP'], $pago['CtaBeneficiario'])
            );
        }

        return true;
    }
}
