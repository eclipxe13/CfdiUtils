<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado;

use CfdiUtils\Nodes\NodeInterface;

/**
 * PAGO31: En un documento relacionado, el número de parcialidad es requerido cuando
 *         el tipo de cambio existe o existe más de un documento relacionado (CRP234)
 */
class NumeroParcialidadRequerido extends AbstractDoctoRelacionadoValidator
{
    protected $code = 'PAGO31';

    protected $title = 'En un documento relacionado, el número de parcialidad es requerido cuando'
        . ' el tipo de cambio existe o existe más de un documento relacionado (CRP233)';

    public function validateDoctoRelacionado(NodeInterface $docto): bool
    {
        if (! $docto->offsetExists('NumParcialidad') && 'PPD' === $docto['MetodoDePagoDR']) {
            throw $this->exception('No hay número de parcialidad y el método de pago es PPD');
        }

        return true;
    }
}
