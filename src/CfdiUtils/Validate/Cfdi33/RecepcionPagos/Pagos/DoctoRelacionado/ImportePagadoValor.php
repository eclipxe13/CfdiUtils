<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Helpers\CalculateDocumentAmountTrait;

/**
 * PAGO27: En un documento relacionado, el importe pagado debe ser mayor a cero (CRP223)
 */
class ImportePagadoValor extends AbstractDoctoRelacionadoValidator
{
    use CalculateDocumentAmountTrait;

    protected $code = 'PAGO27';

    protected $title = 'En un documento relacionado, el importe pagado debe ser mayor a cero (CRP223)';

    public function validateDoctoRelacionado(NodeInterface $docto): bool
    {
        if ($docto->offsetExists('ImpPagado')) {
            $value = (float) $docto['ImpPagado'];
        } else {
            $value = $this->calculateDocumentAmount($docto, $this->getPago());
        }
        if (! $this->isGreaterThan($value, 0)) {
            throw $this->exception(sprintf('ImpPagado: "%s", valor: %F', $docto['ImpPagado'], $value));
        }

        return true;
    }
}
