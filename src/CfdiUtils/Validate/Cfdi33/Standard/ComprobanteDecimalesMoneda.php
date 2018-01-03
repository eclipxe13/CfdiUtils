<?php
namespace CfdiUtils\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Assert;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Cfdi33\Abstracts\AbstractDiscoverableVersion33;
use CfdiUtils\Validate\Status;

/**
 * ComprobanteDecimalesMoneda
 *
 * Valida que:
 * - MONDEC01: El subtotal del comprobante no contiene más de los decimales de la moneda (CFDI33106)
 * - MONDEC02: El descuento del comprobante no contiene más de los decimales de la moneda (CFDI33111)
 * - MONDEC03: El total del comprobante no contiene más de los decimales de la moneda
 * - MONDEC04: El total de impuestos trasladados no contiene más de los decimales de la moneda (CFDI33182)
 * - MONDEC05: El total de impuestos retenidos no contiene más de los decimales de la moneda (CFDI33180)
 */
class ComprobanteDecimalesMoneda extends AbstractDiscoverableVersion33
{
    /** @var Asserts */
    private $asserts;

    /** @var int */
    private $precision;

    /** @var string */
    private $currency;

    private function registerAsserts()
    {
        $asserts = [
            'MONDEC01' => 'El subtotal del comprobante no contiene más de los decimales de la moneda (CFDI33106)',
            'MONDEC02' => 'El descuento del comprobante no contiene más de los decimales de la moneda (CFDI33111)',
            'MONDEC03' => 'El total del comprobante no contiene más de los decimales de la moneda',
            'MONDEC04' => 'El total de impuestos trasladados no contiene más de los decimales de la moneda (CFDI33182)',
            'MONDEC05' => 'El total de impuestos retenidos no contiene más de los decimales de la moneda (CFDI33180)',
        ];
        foreach ($asserts as $code => $title) {
            $this->asserts->put($code, $title);
        }
    }

    public function validate(NodeInterface $comprobante, Asserts $asserts)
    {
        $this->asserts = $asserts;
        $this->registerAsserts();

        $this->currency = $comprobante['Moneda'];
        $precision = $this->knownCurrencyDecimals();
        if (null === $precision) {
            return;
        }
        $this->precision = $precision;

        // SubTotal, Descuento, Total
        $this->validateValue('MONDEC01', $comprobante, 'SubTotal', true);
        $this->validateValue('MONDEC02', $comprobante, 'Descuento');
        $this->validateValue('MONDEC03', $comprobante, 'Total', true);

        $impuestos = $comprobante->searchNode('cfdi:Impuestos');
        if (null !== $impuestos) {
            $this->validateValue('MONDEC04', $impuestos, 'TotalImpuestosTrasladados');
            $this->validateValue('MONDEC05', $impuestos, 'TotalImpuestosRetenidos');
        }
    }

    private function validateValue(string $code, NodeInterface $node, string $attribute, bool $required = false): Assert
    {
        return $this->asserts->putStatus(
            $code,
            Status::when($this->checkValue($node, $attribute, $required)),
            vsprintf('Valor: "%s", Moneda: "%s - %d decimales"', [$node[$attribute], $this->currency, $this->precision])
        );
    }

    private function checkValue(NodeInterface $node, string $attribute, bool $required): bool
    {
        if ($required && ! isset($node[$attribute])) {
            return false;
        }
        $decimals = pathinfo($node[$attribute] ? : '0', PATHINFO_EXTENSION);
        return (strlen($decimals) <= $this->precision);
    }

    /**
     * @return int|null
     */
    private function knownCurrencyDecimals()
    {
        $map = [
            'MXN' => 2,
            'EUR' => 2,
            'USD' => 2,
            'XXX' => 0,
        ];
        return array_key_exists($this->currency, $map) ? $map[$this->currency] : null;
    }
}
