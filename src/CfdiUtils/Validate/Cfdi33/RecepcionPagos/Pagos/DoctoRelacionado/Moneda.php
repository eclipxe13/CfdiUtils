<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado;

use CfdiUtils\Nodes\NodeInterface;

/**
 * PAGO23: En un documento relacionado, la moneda no puede ser "XXX" (CRP217)
 */
class Moneda extends AbstractDoctoRelacionadoValidator
{
    protected $code = 'PAGO23';

    protected $title = 'En un documento relacionado, la moneda no puede ser "XXX" (CRP217)';

    public function validateDoctoRelacionado(NodeInterface $docto): bool
    {
        if ('XXX' === $docto['MonedaDR']) {
            throw $this->exception(sprintf('MonedaDR: "%s"', $docto['MonedaDR']));
        }

        return true;
    }
}
