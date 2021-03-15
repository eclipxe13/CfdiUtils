<?php

namespace CfdiUtils\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Cfdi33\Abstracts\AbstractDiscoverableVersion33;
use CfdiUtils\Validate\Status;

/**
 * ComprobanteMetodoPago
 *
 * Valida que:
 *  - METPAG01: Si el tipo de documento es T ó P, entonces el método de pago no debe existir (CFDI33123)
 *  - METPAG02: Si existe el método de pago entonces debe ser "PUE" o "PPD" (CFDI33121)
 */
class ComprobanteMetodoPago extends AbstractDiscoverableVersion33
{
    private function registerAsserts(Asserts $asserts)
    {
        $assertDescriptions = [
            'METPAG01' => 'Si el tipo de documento es T ó P, entonces el método de pago no debe existir (CFDI33123)',
            'METPAG02' => 'Si existe el método de pago entonces debe ser "PUE" o "PPD" (CFDI33121)',
        ];
        foreach ($assertDescriptions as $code => $title) {
            $asserts->put($code, $title);
        }
    }

    public function validate(NodeInterface $comprobante, Asserts $asserts)
    {
        $this->registerAsserts($asserts);
        $metodoPagoExists = $comprobante->offsetExists('MetodoPago');
        $metodoPago = $comprobante['MetodoPago'];

        $tipoDeComprobante = $comprobante['TipoDeComprobante'];
        if (in_array($tipoDeComprobante, ['T', 'P'], true)) {
            $asserts->putStatus(
                'METPAG01',
                Status::when(! $metodoPagoExists),
                sprintf('TipoDeComprobante: %s, MetodoPago: %s', $tipoDeComprobante, $metodoPago)
            );
        }

        if ($metodoPagoExists) {
            $asserts->putStatus(
                'METPAG02',
                Status::when(in_array($metodoPago, ['PUE', 'PPD'], true)),
                sprintf('MetodoPago: %s', $metodoPago)
            );
        }
    }
}
