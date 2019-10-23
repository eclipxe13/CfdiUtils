<?php

namespace CfdiUtils\Validate\Cfdi33\Abstracts;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Contracts\DiscoverableCreateInterface;
use CfdiUtils\Validate\Contracts\ValidatorInterface;

abstract class AbstractRecepcionPagos10 extends AbstractVersion33 implements DiscoverableCreateInterface
{
    abstract public function validateRecepcionPagos(NodeInterface $comprobante, Asserts $asserts);

    public function validate(NodeInterface $comprobante, Asserts $asserts)
    {
        // do not run anything if not found
        $pagos10 = $comprobante->searchNode('cfdi:Complemento', 'pago10:Pagos');
        if ('3.3' !== $comprobante['Version']
            || 'P' !== $comprobante['TipoDeComprobante']
            || null === $pagos10
            || '1.0' !== $pagos10['Version']
        ) {
            return;
        }
        $this->validateRecepcionPagos($comprobante, $asserts);
    }

    public static function createDiscovered(): ValidatorInterface
    {
        return new static();
    }
}
