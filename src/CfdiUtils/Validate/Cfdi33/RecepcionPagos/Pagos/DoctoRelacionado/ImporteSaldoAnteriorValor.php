<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado;

use CfdiUtils\Nodes\NodeInterface;

/**
 * PAGO26: En un documento relacionado, el importe del saldo anterior debe ser mayor a cero (CRP221)
 */
class ImporteSaldoAnteriorValor extends AbstractDoctoRelacionadoValidator
{
    protected $code = 'PAGO26';

    protected $title = 'En un documento relacionado, el importe del saldo anterior debe ser mayor a cero (CRP221)';

    public function validateDoctoRelacionado(NodeInterface $docto): bool
    {
        $value = (float) $docto['ImpSaldoAnt'];
        if (! $this->isGreaterThan($value, 0)) {
            throw $this->exception(sprintf('ImpSaldoAnt: "%s"', $docto['ImpSaldoAnt']));
        }

        return true;
    }
}
