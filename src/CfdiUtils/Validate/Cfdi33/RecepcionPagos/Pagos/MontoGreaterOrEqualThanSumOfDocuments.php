<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Utils\CurrencyDecimals;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Helpers\CalculateDocumentAmountTrait;

/**
 * PAGO30: En un pago, la suma de los valores registrados o predeterminados en el importe pagado
 *         de los documentos relacionados debe ser menor o igual que el monto del pago (CRP206)
 */
class MontoGreaterOrEqualThanSumOfDocuments extends AbstractPagoValidator
{
    use CalculateDocumentAmountTrait;

    protected $code = 'PAGO30';

    protected $title = 'En un pago, la suma de los valores registrados o predeterminados en el importe pagado'
        . ' de los documentos relacionados debe ser menor o igual que el monto del pago (CRP206)';

    public function validatePago(NodeInterface $pago): bool
    {
        $currency = $this->createCurrencyDecimals($pago['MonedaP']);
        $sumOfDocuments = $this->calculateSumOfDocuments($pago, $currency);
        $pagoAmount = (float) $pago['Monto'];
        if ($this->isGreaterThan($sumOfDocuments, $pagoAmount)) {
            throw new ValidatePagoException(
                sprintf('Monto del pago: "%s", Suma de documentos: "%s"', $pagoAmount, $sumOfDocuments)
            );
        }

        return true;
    }

    public function calculateSumOfDocuments(NodeInterface $pago, CurrencyDecimals $currency): float
    {
        $sumOfDocuments = 0;
        $documents = $pago->searchNodes('pago10:DoctoRelacionado');
        foreach ($documents as $document) {
            $exchangeRate = (float) $document['TipoCambioDR'];
            if ($this->isEqual($exchangeRate, 0)) {
                $exchangeRate = 1;
            }
            $sumOfDocuments += $currency->round($this->calculateDocumentAmount($document, $pago) / $exchangeRate);
        }
        return $sumOfDocuments;
    }
}
