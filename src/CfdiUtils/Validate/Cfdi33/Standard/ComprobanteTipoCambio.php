<?php

namespace CfdiUtils\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Cfdi33\Abstracts\AbstractDiscoverableVersion33;
use CfdiUtils\Validate\Status;

/**
 * ComprobanteTipoCambio
 *
 * Valida que:
 * - TIPOCAMBIO01: La moneda exista y no tenga un valor vacío
 * - TIPOCAMBIO02: Si la moneda es "MXN", entonces el tipo de cambio debe tener el valor "1"
 *                 o no debe existir (CFDI33113)
 * - TIPOCAMBIO03: Si la moneda es "XXX", entonces el tipo de cambio no debe existir (CFDI33115)
 * - TIPOCAMBIO04: Si la moneda no es "MXN" ni "XXX", entonces el tipo de cambio entonces
 *                 el tipo de cambio debe seguir el patrón [0-9]{1,18}(.[0-9]{1,6})? (CFDI33114, CFDI33117)
 */
class ComprobanteTipoCambio extends AbstractDiscoverableVersion33
{
    private function registerAsserts(Asserts $asserts)
    {
        $assertDescriptions = [
            'TIPOCAMBIO01' => 'La moneda exista y no tenga un valor vacío',
            'TIPOCAMBIO02' => 'Si la moneda es "MXN", entonces el tipo de cambio debe tener el valor "1"'
                            . ' o no debe existir (CFDI33113)',
            'TIPOCAMBIO03' => 'Si la moneda es "XXX", entonces el tipo de cambio no debe existir (CFDI33115)',
            'TIPOCAMBIO04' => 'Si la moneda no es "MXN" ni "XXX", entonces el tipo de cambio'
                            . ' debe seguir el patrón [0-9]{1,18}(.[0-9]{1,6}?) (CFDI33114, CFDI33117)',
        ];
        foreach ($assertDescriptions as $code => $title) {
            $asserts->put($code, $title);
        }
    }

    public function validate(NodeInterface $comprobante, Asserts $asserts)
    {
        $this->registerAsserts($asserts);

        $existsTipoCambio = $comprobante->offsetExists('TipoCambio');
        $tipoCambio = $comprobante['TipoCambio'];
        $moneda = $comprobante['Moneda'];

        $asserts->putStatus('TIPOCAMBIO01', Status::when('' !== $moneda));
        if ('' === $moneda) {
            return;
        }

        if ('MXN' === $moneda) {
            $asserts->putStatus(
                'TIPOCAMBIO02',
                Status::when(! $existsTipoCambio || abs(floatval($tipoCambio) - 1) < 0.0000001)
            );
        }

        if ('XXX' === $moneda) {
            $asserts->putStatus('TIPOCAMBIO03', Status::when(! $existsTipoCambio));
        }

        if ('MXN' !== $moneda && 'XXX' !== $moneda) {
            $pattern = '/^[0-9]{1,18}(\.[0-9]{1,6})?$/';
            $asserts->putStatus('TIPOCAMBIO04', Status::when((bool) preg_match($pattern, $tipoCambio)));
        }
    }
}
