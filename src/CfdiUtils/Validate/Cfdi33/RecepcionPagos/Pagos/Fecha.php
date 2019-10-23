<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Cfdi33\Utils\AssertFechaFormat;

/**
 * PAGO02: En un pago, la fecha debe cumplir con el formato específico
 */
class Fecha extends AbstractPagoValidator
{
    protected $code = 'PAGO02';

    protected $title = 'En un pago, la fecha debe cumplir con el formato específico';

    public function validatePago(NodeInterface $pago): bool
    {
        if (! AssertFechaFormat::hasFormat($pago['FechaPago'])) {
            throw new ValidatePagoException(sprintf('FechaPago: "%s"', $pago['FechaPago']));
        }

        return true;
    }
}
