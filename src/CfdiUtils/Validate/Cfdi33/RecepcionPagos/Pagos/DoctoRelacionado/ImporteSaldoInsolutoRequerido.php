<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado;

use CfdiUtils\Nodes\NodeInterface;

/**
 * PAGO33: En un documento relacionado, el saldo insoluto es requerido cuando
 *         el tipo de cambio existe o existe más de un documento relacionado (CRP234)
 */
class ImporteSaldoInsolutoRequerido extends AbstractDoctoRelacionadoValidator
{
    protected string $code = 'PAGO33';

    protected string $title = 'En un documento relacionado, el saldo insoluto es requerido cuando'
        . ' el tipo de cambio existe o existe más de un documento relacionado (CRP233)';

    public function validateDoctoRelacionado(NodeInterface $docto): bool
    {
        if (! $docto->exists('ImpSaldoInsoluto') && 'PPD' === $docto['MetodoDePagoDR']) {
            throw $this->exception('No hay saldo insoluto y el método de pago es PPD');
        }

        return true;
    }
}
