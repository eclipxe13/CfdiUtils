<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Nodes\NodeInterface;

/**
 * PAGO03: En un pago, la forma de pago debe existir y no puede ser "99" (CRP201)
 */
class FormaDePago extends AbstractPagoValidator
{
    protected $code = 'PAGO03';

    protected $title = 'En un pago, la forma de pago debe existir y no puede ser "99" (CRP201)';

    public function validatePago(NodeInterface $pago): bool
    {
        try {
            $paymentType = $this->createPaymentType($pago['FormaDePagoP']);
            if ('99' === $paymentType->key()) {
                throw new ValidatePagoException('Cannot be "99"');
            }
        } catch (ValidatePagoException $exception) {
            throw new ValidatePagoException(
                sprintf('FormaDePagoP: "%s" %s', $pago['FormaDePagoP'], $exception->getMessage())
            );
        }
        return true;
    }
}
