<?php

namespace CfdiUtils\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Cfdi33\Abstracts\AbstractDiscoverableVersion33;
use CfdiUtils\Validate\Status;

/**
 * ComprobanteTotal
 *
 * Valida que:
 * - TOTAL01: El atributo Total existe, no está vacío y cumple con el patrón [0-9]+(.[0-9]+)?
 */
class ComprobanteTotal extends AbstractDiscoverableVersion33
{
    public function validate(NodeInterface $comprobante, Asserts $asserts)
    {
        $pattern = '/^[0-9]+(\.[0-9]+)?$/';
        $asserts->put(
            'TOTAL01',
            'El atributo Total existe, no está vacío y cumple con el patrón [0-9]+(.[0-9]+)?',
            Status::when('' !== $comprobante['Total'] && (bool) preg_match($pattern, $comprobante['Total']))
        );
    }
}
