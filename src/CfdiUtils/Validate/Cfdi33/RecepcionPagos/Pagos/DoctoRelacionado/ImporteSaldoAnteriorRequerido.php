<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado;

use CfdiUtils\Nodes\NodeInterface;

/**
 * PAGO32: En un documento relacionado, el saldo anterior es requerido cuando
 *         el tipo de cambio existe o existe más de un documento relacionado (CRP234)
 */
class ImporteSaldoAnteriorRequerido extends AbstractDoctoRelacionadoValidator
{
    protected $code = 'PAGO32';

    protected $title = 'En un documento relacionado, el saldo anterior es requerido cuando'
        . ' el tipo de cambio existe o existe más de un documento relacionado (CRP234)';

    public function validateDoctoRelacionado(NodeInterface $docto): bool
    {
        if (! $docto->offsetExists('ImpSaldoAnt') && 'PPD' === $docto['MetodoDePagoDR']) {
            throw $this->exception('No hay saldo anterior y el método de pago es PPD');
        }

        return true;
    }
}
