<?php
namespace CfdiUtils\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Cfdi33\Abstracts\AbstractDiscoverableVersion33;
use CfdiUtils\Validate\Status;

/**
 * ComprobanteFormaPago
 *
 * Valida que:
 * - FORMAPAGO01: El campo forma de pago no debe existir cuando existe el complemento para recepción de pagos
 *                (CFDI33103)
 *
 * Nota: Aunque no es específica la documentación, se considera un error que no existan
 * el atributo FormaPago y tampoco el complemento para la recepción de pagos.
 */
class ComprobanteFormaPago extends AbstractDiscoverableVersion33
{
    public function validate(NodeInterface $comprobante, Asserts $asserts)
    {
        $existsComplementoPagos = (null !== $comprobante->searchNode('cfdi:Complemento', 'pago10:Pagos'));
        $existsFormaPago = isset($comprobante['FormaPago']);

        $asserts->put(
            'FORMAPAGO01',
            'El campo forma de pago no debe existir cuando existe el complemento para recepción de pagos (CFDI33103)',
            Status::when($existsComplementoPagos xor $existsFormaPago)
        );
    }
}
