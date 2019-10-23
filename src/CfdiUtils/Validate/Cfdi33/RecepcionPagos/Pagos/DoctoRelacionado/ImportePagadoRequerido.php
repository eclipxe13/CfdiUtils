<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado;

use CfdiUtils\Nodes\NodeInterface;

/**
 * PAGO30: En un documento relacionado, el importe pagado es requerido cuando
 *         el tipo de cambio existe o existe más de un documento relacionado (CRP235)
 */
class ImportePagadoRequerido extends AbstractDoctoRelacionadoValidator
{
    protected $code = 'PAGO30';

    protected $title = 'En un documento relacionado, el importe pagado es requerido cuando'
        . ' el tipo de cambio existe o existe más de un documento relacionado (CRP235)';

    public function validateDoctoRelacionado(NodeInterface $docto): bool
    {
        if (! $docto->offsetExists('ImpPagado')) {
            $documentsCount = $this->getPago()->searchNodes('pago10:DoctoRelacionado')->count();
            if ($documentsCount > 1) {
                throw $this->exception('No hay importe pagado y hay más de 1 documento en el pago');
            }
            if ($docto->offsetExists('TipoCambioDR')) {
                throw $this->exception('No hay importe pagado y existe el tipo de cambio del documento');
            }
        }

        return true;
    }
}
