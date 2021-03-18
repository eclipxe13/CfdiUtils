<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Utils\CurrencyDecimals;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Helpers\CalculateDocumentAmountTrait;

/**
 * PAGO09: En un pago, el monto del pago debe encontrarse entre límites mínimo y máximo de la suma
 *         de los valores registrados en el importe pagado de los documentos relacionados (Guía llenado)
 */
class MontoBetweenIntervalSumOfDocuments extends AbstractPagoValidator
{
    use CalculateDocumentAmountTrait;

    protected $code = 'PAGO09';

    protected $title = 'En un pago, el monto del pago debe encontrarse entre límites mínimo y máximo de la suma'
        . ' de los valores registrados en el importe pagado de los documentos relacionados (Guía llenado)';

    public function validatePago(NodeInterface $pago): bool
    {
        $pagoAmount = floatval($pago['Monto']);
        $bounds = $this->calculateDocumentsAmountBounds($pago);
        $currencyDecimals = CurrencyDecimals::newFromKnownCurrencies($pago['MonedaP'], 2);
        $lower = $currencyDecimals->round($bounds['lower']);
        $upper = $currencyDecimals->round($bounds['upper']);
        if ($pagoAmount < $lower || $pagoAmount > $upper) {
            throw new ValidatePagoException(
                sprintf('Monto del pago: "%s", Suma mínima: "%s", Suma máxima: "%s"', $pagoAmount, $lower, $upper)
            );
        }

        return true;
    }

    public function calculateDocumentsAmountBounds(NodeInterface $pago): array
    {
        $documents = $pago->searchNodes('pago10:DoctoRelacionado');
        $values = [];
        foreach ($documents as $document) {
            $values[] = $this->calculateDocumentAmountBounds($document, $pago);
        }
        return [
            'lower' => array_sum(array_column($values, 'lower')),
            'upper' => array_sum(array_column($values, 'upper')),
        ];
    }

    public function calculateDocumentAmountBounds(NodeInterface $doctoRelacionado, NodeInterface $pago): array
    {
        $amount = $this->calculateDocumentAmount($doctoRelacionado, $pago);
        $impPagado = $doctoRelacionado['ImpPagado'] ?? $amount;
        $tipoCambioDr = $doctoRelacionado['TipoCambioDR'];
        $exchangeRate = 1;
        if ('' !== $tipoCambioDr && $pago['MonedaP'] !== $pago['MonedaDR']) {
            $exchangeRate = floatval($tipoCambioDr);
        }
        $numDecimalsAmount = $this->getNumDecimals($impPagado);
        $numDecimalsExchangeRate = $this->getNumDecimals($tipoCambioDr);

        if (0 === $numDecimalsExchangeRate) {
            return [
                'lower' => $amount / $exchangeRate,
                'upper' => $amount / $exchangeRate,
            ];
        }

        $almostTwo = 2 - (10 ** - 10);

        $lowerAmount = $amount - 10 ** - $numDecimalsAmount / 2;
        $lowerExchangeRate = $exchangeRate + (10 ** (- $numDecimalsExchangeRate) / $almostTwo);

        $upperAmount = $amount + 10 ** - $numDecimalsAmount / $almostTwo;
        $upperExchangeRate = $exchangeRate - (10 ** (- $numDecimalsExchangeRate) / 2);

        return [
            'lower' => $lowerAmount / $lowerExchangeRate,
            'upper' => $upperAmount / $upperExchangeRate,
        ];
    }

    public function getNumDecimals(string $numeric): int
    {
        if (! is_numeric($numeric)) {
            return 0;
        }
        $pointPosition = strpos($numeric, '.');
        if (false === $pointPosition) {
            return 0;
        }
        return strlen($numeric) - 1 - $pointPosition;
    }
}
