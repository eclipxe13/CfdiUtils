<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Nodes\NodeInterface;

/**
 * PAGO20: En un pago, si existe el tipo de cadena de pago debe existir
 *         el certificado del pago y viceversa (CRP227 y CRP228)
 */
class TipoCadenaPagoCertificado extends AbstractPagoValidator
{
    protected $code = 'PAGO20';

    protected $title = 'En un pago, si existe el tipo de cadena de pago debe existir'
        . ' el certificado del pago y viceversa (CRP227 y CRP228)';

    public function validatePago(NodeInterface $pago): bool
    {
        if ((('' !== $pago['TipoCadPago']) xor ('' !== $pago['CertPago']))
            || ($pago->offsetExists('TipoCadPago') xor $pago->offsetExists('CertPago'))) {
            throw new ValidatePagoException(
                sprintf('Tipo cadena pago: "%s", Certificado: "%s"', $pago['TipoCadPago'], $pago['CertPago'])
            );
        }

        return true;
    }
}
