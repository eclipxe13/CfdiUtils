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
 *  - METPAG01: Si el tipo de documento es T, P ó N, entonces el metodo de pago no debe existir
 *              (CFDI33123, CFDI33124)
 *  - METPAG02: Si el tipo de documento es I ó E, entonces el metodo de pago debe ser "PUE" o "PPD"
 *              (CFDI33121, CFDI33122)
 */
class ComprobanteMetodoPago extends AbstractDiscoverableVersion33
{
    private function registerAsserts(Asserts $asserts)
    {
        $assertDescriptions = [
            'METPAG01' => 'Si el tipo de documento es T, P ó N, entonces el metodo de pago'
                       . ' no debe existir(CFDI33123, CFDI33124)',
            'METPAG02' => 'Si el tipo de documento es I ó E, entonces el metodo de pago'
                       . ' debe ser "PUE" o "PPD" (CFDI33121, CFDI33122)',
        ];
        foreach ($assertDescriptions as $code => $title) {
            $asserts->put($code, $title);
        }
    }

    public function validate(NodeInterface $comprobante, Asserts $asserts)
    {
        $this->registerAsserts($asserts);
        $tipoDeComprobante = $comprobante['TipoDeComprobante'];

        if ('T' === $tipoDeComprobante || 'P' === $tipoDeComprobante || 'N' === $tipoDeComprobante) {
            $asserts->putStatus(
                'METPAG01',
                Status::when(! $comprobante->offsetExists('MetodoPago'))
            );
        }

        if ('I' === $tipoDeComprobante || 'E' === $tipoDeComprobante) {
            $allowedMetodoPago = ['PUE', 'PPD'];
            $asserts->putStatus(
                'METPAG02',
                Status::when(in_array($comprobante['MetodoPago'], $allowedMetodoPago, true))
            );
        }
    }
}
