<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado;

use CfdiUtils\Nodes\NodeInterface;

/**
 * PAGO29: En un documento relacionado, los importes de importe pagado, saldo anterior y saldo insoluto
 *         deben tener hasta la cantidad de decimales que soporte la moneda (CRP222, CRP224, CRP225)
 */
class ImportesDecimales extends AbstractDoctoRelacionadoValidator
{
    protected $code = 'PAGO29';

    protected $title = 'En un documento relacionado, los importes de importe pagado, saldo anterior y saldo insoluto'
        . ' deben tener hasta la cantidad de decimales que soporte la moneda (CRP222, CRP224, CRP225)';

    public function validateDoctoRelacionado(NodeInterface $docto): bool
    {
        $currency = $this->createCurrencyDecimals($docto['MonedaDR']);

        if (! $currency->doesNotExceedDecimals($docto['ImpSaldoAnt'])) {
            throw $this->exception(
                sprintf('ImpSaldoAnt: "%s", Decimales: %d', $docto['ImpSaldoAnt'], $currency->decimals())
            );
        }

        if ($docto->offsetExists('ImpPagado') && ! $currency->doesNotExceedDecimals($docto['ImpPagado'])) {
            throw $this->exception(
                sprintf('ImpPagado: "%s", Decimales: %d', $docto['ImpPagado'], $currency->decimals())
            );
        }

        if (! $currency->doesNotExceedDecimals($docto['ImpSaldoInsoluto'])) {
            throw $this->exception(
                sprintf('ImpSaldoInsoluto: "%s", Decimales: %d', $docto['ImpSaldoInsoluto'], $currency->decimals())
            );
        }

        return true;
    }
}
