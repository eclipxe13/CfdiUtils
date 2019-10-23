<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Helpers\CalculateDocumentAmountTrait;

/**
 * PAGO28: En un documento relacionado, el importe del saldo insoluto debe ser mayor o igual a cero
 *         e igual a la resta del importe del saldo anterior menos el importe pagado (CRP226)
 */
class ImporteSaldoInsolutoValor extends AbstractDoctoRelacionadoValidator
{
    use CalculateDocumentAmountTrait;

    protected $code = 'PAGO28';

    protected $title = 'En un documento relacionado, el importe del saldo insoluto debe ser mayor o igual a cero'
        . ' e igual a la resta del importe del saldo anterior menos el importe pagado (CRP226)';

    public function validateDoctoRelacionado(NodeInterface $docto): bool
    {
        $value = (float) $docto['ImpSaldoInsoluto'];
        if (! $this->isEqual(0, $value) && ! $this->isGreaterThan($value, 0)) {
            throw $this->exception(sprintf('ImpSaldoInsoluto: "%s"', $docto['ImpSaldoInsoluto']));
        }

        $expected = (float) $docto['ImpSaldoAnt'] - $this->calculateDocumentAmount($docto, $this->getPago());
        if (! $this->isEqual($value, $expected)) {
            throw $this->exception(
                sprintf('ImpSaldoInsoluto: "%s", Esperado: %F', $docto['ImpSaldoInsoluto'], $expected)
            );
        }

        return true;
    }
}
