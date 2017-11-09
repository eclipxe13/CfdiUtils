<?php
namespace CfdiUtils\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Cfdi33\Abstracts\AbstractDiscoverableVersion33;
use CfdiUtils\Validate\Status;

/**
 * ConceptoDescuento.php
 *
 * Valida que:
 * - CONCEPDESC01: Si existe el atributo descuento entonces debe ser menor o igual que el subtotal
 *                 y mayor o igual que cero
 */
class ConceptoDescuento extends AbstractDiscoverableVersion33
{
    public function validate(NodeInterface $comprobante, Asserts $asserts)
    {
        $asserts->put(
            'CONCEPDESC01',
            'Si existe el atributo descuento entonces debe ser menor o igual que el subtotal y mayor o igual que cero'
        );
        if (isset($comprobante['Descuento'])) {
            $descuento = (float) $comprobante['Descuento'];
            $subtotal = (float) $comprobante['SubTotal'];
            $asserts->putStatus(
                'CONCEPDESC01',
                Status::when('' !== $comprobante['Descuento'] && $descuento >= 0 && $descuento <= $subtotal),
                sprintf('Descuento: "%s", SubTotal: "%s"', $comprobante['Descuento'], $comprobante['SubTotal'])
            );
        }
    }
}
