<?php

namespace CfdiUtils\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Cfdi33\Abstracts\AbstractDiscoverableVersion33;
use CfdiUtils\Validate\Status;

/**
 * ComprobanteTipoDeComprobante
 *
 * Valida que:
 * - TIPOCOMP01: Si el tipo de comprobante es T, P ó N, entonces no debe existir las condiciones de pago
 * - TIPOCOMP02: Si el tipo de comprobante es T, P ó N, entonces no debe existir la definición de impuestos (CFDI33179)
 * - TIPOCOMP03: Si el tipo de comprobante es T, P ó N, entonces no debe existir la forma de pago
 * - TIPOCOMP04: Si el tipo de comprobante es T, P ó N, entonces no debe existir el método de pago (CFDI33123)
 * - TIPOCOMP05: Si el tipo de comprobante es T ó P, entonces no debe existir el descuento del comprobante (CFDI33110)
 * - TIPOCOMP06: Si el tipo de comprobante es T ó P, entonces no debe existir el descuento de los conceptos (CFDI33179)
 * - TIPOCOMP07: Si el tipo de comprobante es T ó P, entonces el subtotal debe ser cero (CFDI33108)
 * - TIPOCOMP08: Si el tipo de comprobante es T ó P, entonces el total debe ser cero
 * - TIPOCOMP09: Si el tipo de comprobante es I, E ó N, entonces el valor unitario de todos los conceptos
 *               debe ser mayor que cero
 * - TIPOCOMP010: Si el tipo de comprobante es N, entonces la moneda debe ser MXN
 */
class ComprobanteTipoDeComprobante extends AbstractDiscoverableVersion33
{
    private function registerAsserts(Asserts $asserts)
    {
        $assertsDescriptions = [
            'TIPOCOMP01' => 'Si el tipo de comprobante es T, P ó N,'
                         . ' entonces no debe existir las condiciones de pago',
            'TIPOCOMP02' => 'Si el tipo de comprobante es T, P ó N,'
                         . ' entonces no debe existir la definición de impuestos (CFDI33179)',
            'TIPOCOMP03' => 'Si el tipo de comprobante es T, P ó N, entonces no debe existir la forma de pago',
            'TIPOCOMP04' => 'Si el tipo de comprobante es T, P ó N,'
                         . ' entonces no debe existir el método de pago (CFDI33123)',

            'TIPOCOMP05' => 'Si el tipo de comprobante es T ó P,'
                         . ' entonces no debe existir el descuento del comprobante (CFDI33110)',
            'TIPOCOMP06' => 'Si el tipo de comprobante es T ó P,'
                         . ' entonces no debe existir el descuento de los conceptos (CFDI33179)',
            'TIPOCOMP07' => 'Si el tipo de comprobante es T ó P, entonces el subtotal debe ser cero (CFDI33108)',
            'TIPOCOMP08' => 'Si el tipo de comprobante es T ó P entonces el total debe ser cero',

            'TIPOCOMP09' => 'Si el tipo de comprobante es I, E ó N,'
                         . ' entonces el valor unitario de todos los conceptos debe ser mayor que cero',

            'TIPOCOMP10' => 'Si el tipo de comprobante es N entonces, la moneda debe ser MXN',
        ];
        foreach ($assertsDescriptions as $code => $title) {
            $asserts->put($code, $title);
        }
    }

    public function validate(NodeInterface $comprobante, Asserts $asserts)
    {
        $this->registerAsserts($asserts);

        $tipoComprobante = $comprobante['TipoDeComprobante'];

        if ('T' === $tipoComprobante || 'P' === $tipoComprobante || 'N' === $tipoComprobante) {
            $asserts->putStatus(
                'TIPOCOMP01',
                Status::when(! $comprobante->offsetExists('CondicionesDePago'))
            );
            $asserts->putStatus(
                'TIPOCOMP02',
                Status::when(null === $comprobante->searchNode('cfdi:Impuestos'))
            );
            $asserts->putStatus(
                'TIPOCOMP03',
                Status::when(! $comprobante->offsetExists('FormaPago'))
            );
            $asserts->putStatus(
                'TIPOCOMP04',
                Status::when(! $comprobante->offsetExists('MetodoPago'))
            );
        }

        if ('T' === $tipoComprobante || 'P' === $tipoComprobante) {
            $asserts->putStatus(
                'TIPOCOMP05',
                Status::when(! $comprobante->offsetExists('Descuento'))
            );
            $asserts->putStatus(
                'TIPOCOMP06',
                Status::when($this->checkConceptosDoesNotHaveDescuento($comprobante))
            );
            $asserts->putStatus(
                'TIPOCOMP07',
                Status::when($this->isZero($comprobante['SubTotal']))
            );
            $asserts->putStatus(
                'TIPOCOMP08',
                Status::when($this->isZero($comprobante['Total']))
            );
        }
        if ('I' === $tipoComprobante || 'E' === $tipoComprobante || 'N' === $tipoComprobante) {
            $asserts->putStatus(
                'TIPOCOMP09',
                Status::when($this->checkConceptosValorUnitarioIsGreaterThanZero($comprobante))
            );
        }
        if ('N' === $tipoComprobante) {
            $asserts->putStatus(
                'TIPOCOMP10',
                Status::when('MXN' === $comprobante['Moneda'])
            );
        }
    }

    private function checkConceptosDoesNotHaveDescuento(NodeInterface $comprobante): bool
    {
        foreach ($comprobante->searchNodes('cfdi:Conceptos', 'cfdi:Concepto') as $concepto) {
            if ($concepto->offsetExists('Descuento')) {
                return false;
            }
        }
        return true;
    }

    private function checkConceptosValorUnitarioIsGreaterThanZero(NodeInterface $comprobante): bool
    {
        foreach ($comprobante->searchNodes('cfdi:Conceptos', 'cfdi:Concepto') as $concepto) {
            if (! $this->isGreaterThanZero($concepto['ValorUnitario'])) {
                return false;
            }
        }
        return true;
    }

    private function isZero(string $value): bool
    {
        if ('' === $value || ! is_numeric($value)) {
            return false;
        }
        return (abs((float) $value) < 0.0000001);
    }

    private function isGreaterThanZero(string $value): bool
    {
        if ('' === $value || ! is_numeric($value)) {
            return false;
        }
        return (abs((float) $value) > 0.0000001);
    }
}
