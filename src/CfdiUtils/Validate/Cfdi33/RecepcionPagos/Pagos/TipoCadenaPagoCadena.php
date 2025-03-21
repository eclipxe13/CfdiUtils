<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Nodes\NodeInterface;

/**
 * PAGO21: En un pago, si existe el tipo de cadena de pago debe existir
 *         la cadena del pago y viceversa (CRP229 y CRP230)
 */
class TipoCadenaPagoCadena extends AbstractPagoValidator
{
    protected string $code = 'PAGO21';

    protected string $title = 'En un pago, si existe el tipo de cadena de pago debe existir'
        . ' la cadena del pago y viceversa (CRP229 y CRP230)';

    public function validatePago(NodeInterface $pago): bool
    {
        if (
            (('' !== $pago['TipoCadPago']) xor ('' !== $pago['CadPago']))
            || ($pago->exists('TipoCadPago') xor $pago->exists('CadPago'))
        ) {
            throw new ValidatePagoException(
                sprintf('Tipo cadena pago: "%s", Cadena: "%s"', $pago['TipoCadPago'], $pago['CadPago'])
            );
        }

        return true;
    }
}
