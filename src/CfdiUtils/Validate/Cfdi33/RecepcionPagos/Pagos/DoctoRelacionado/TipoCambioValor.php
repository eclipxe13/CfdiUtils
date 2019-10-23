<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado;

use CfdiUtils\Nodes\NodeInterface;

/**
 * PAGO25: En un documento relacionado, el tipo de cambio debe tener el valor "1"
 *         cuando la moneda del documento es MXN y diferente de la moneda del pago (CRP220)
 */
class TipoCambioValor extends AbstractDoctoRelacionadoValidator
{
    protected $code = 'PAGO25';

    protected $title = 'En un documento relacionado, el tipo de cambio debe tener el valor "1"'
        . ' cuando la moneda del documento es MXN y diferente de la moneda del pago (CRP220)';

    public function validateDoctoRelacionado(NodeInterface $docto): bool
    {
        $pago = $this->getPago();

        if ('MXN' === $docto['MonedaDR']
            && $pago['MonedaP'] !== $docto['MonedaDR']
            && '1' !== $docto['TipoCambioDR']) {
            throw $this->exception(sprintf(
                'Moneda pago: "%s", Moneda documento: "%s", Tipo cambio docto: "%s"',
                $pago['MonedaP'],
                $docto['MonedaDR'],
                $docto['TipoCambioDR']
            ));
        }

        return true;
    }
}
