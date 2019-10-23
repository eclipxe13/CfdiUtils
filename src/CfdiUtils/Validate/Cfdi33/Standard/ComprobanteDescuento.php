<?php

namespace CfdiUtils\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Cfdi33\Abstracts\AbstractDiscoverableVersion33;
use CfdiUtils\Validate\Status;

/**
 * ComprobanteDescuento
 *
 * Valida que:
 * - DESCUENTO01: Si existe el atributo descuento, entonces debe ser menor o igual que el subtotal
 *                 y mayor o igual que cero (CFDI33109)
 */
class ComprobanteDescuento extends AbstractDiscoverableVersion33
{
    public function validate(NodeInterface $comprobante, Asserts $asserts)
    {
        $asserts->put(
            'DESCUENTO01',
            'Si existe el atributo descuento,'
              . ' entonces debe ser menor o igual que el subtotal y mayor o igual que cero (CFDI33109)'
        );
        if ($comprobante->offsetExists('Descuento')) {
            $descuento = (float) $comprobante['Descuento'];
            $subtotal = (float) $comprobante['SubTotal'];
            $asserts->putStatus(
                'DESCUENTO01',
                Status::when('' !== $comprobante['Descuento'] && $descuento >= 0 && $descuento <= $subtotal),
                sprintf('Descuento: "%s", SubTotal: "%s"', $comprobante['Descuento'], $comprobante['SubTotal'])
            );
        }
    }
}
