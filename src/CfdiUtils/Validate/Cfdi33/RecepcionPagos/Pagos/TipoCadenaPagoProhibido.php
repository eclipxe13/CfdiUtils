<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Nodes\NodeInterface;

/**
 * PAGO19: En un pago, cuando la forma de pago no sea 03 o 99 el tipo de cadena de pago no debe existir (CRP216)
 */
class TipoCadenaPagoProhibido extends AbstractPagoValidator
{
    protected $code = 'PAGO19';

    protected $title = 'En un pago, cuando la forma de pago no sea 03 o 99'
        . ' el tipo de cadena de pago no debe existir (CRP216)';

    public function validatePago(NodeInterface $pago): bool
    {
        $payment = $this->createPaymentType($pago['FormaDePagoP']);

        // si NO es banzarizado y está establecida la cuenta ordenante existe
        if (! $payment->allowPaymentSignature() && $pago->offsetExists('TipoCadPago')) {
            throw new ValidatePagoException(
                sprintf('Forma de pago: "%s", Tipo cadena pago: "%s"', $pago['FormaDePagoP'], $pago['TipoCadPago'])
            );
        }

        return true;
    }
}
