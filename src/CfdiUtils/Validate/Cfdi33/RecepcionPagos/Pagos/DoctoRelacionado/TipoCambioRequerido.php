<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado;

use CfdiUtils\Nodes\NodeInterface;

/**
 * PAGO24: En un documento relacionado, el tipo de cambio debe existir cuando la moneda del pago
 *         es diferente a la moneda del documento y viceversa (CRP218, CRP219)
 */
class TipoCambioRequerido extends AbstractDoctoRelacionadoValidator
{
    protected $code = 'PAGO24';

    protected $title = 'En un documento relacionado, el tipo de cambio debe existir cuando la moneda del pago'
        . ' es diferente a la moneda del documento y viceversa (CRP218, CRP219)';

    public function validateDoctoRelacionado(NodeInterface $docto): bool
    {
        $pago = $this->getPago();
        $currencyIsEqual = $pago['MonedaP'] === $docto['MonedaDR'];
        if (! ($currencyIsEqual xor $docto->offsetExists('TipoCambioDR'))) {
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
