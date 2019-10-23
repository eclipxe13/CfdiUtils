<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Utils\Rfc;

/**
 * PAGO11: En un pago, cuando el RFC del banco emisor sea "XEXX010101000" el nombre del banco es requerido (CRP211)
 */
class BancoOrdenanteNombreRequerido extends AbstractPagoValidator
{
    protected $code = 'PAGO11';

    protected $title = 'En un pago, cuando el RFC del banco emisor sea "XEXX010101000"'
        . ' el nombre del banco es requerido (CRP211)';

    public function validatePago(NodeInterface $pago): bool
    {
        if (Rfc::RFC_FOREIGN === $pago['RfcEmisorCtaOrd'] && '' === $pago['NomBancoOrdExt']) {
            throw new ValidatePagoException(
                sprintf('Rfc: "%s, Nombre: %s"', $pago['RfcEmisorCtaOrd'], $pago['NomBancoOrdExt'])
            );
        }

        return true;
    }
}
