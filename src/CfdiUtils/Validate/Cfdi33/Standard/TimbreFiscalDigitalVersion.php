<?php

namespace CfdiUtils\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Cfdi33\Abstracts\AbstractDiscoverableVersion33;
use CfdiUtils\Validate\Status;

/**
 * TimbreFiscalDigitalVersion
 *
 * Valida que:
 * - TFDVERSION01: Si existe el complemento timbre fiscal digital, entonces su versión debe ser 1.1
 */
class TimbreFiscalDigitalVersion extends AbstractDiscoverableVersion33
{
    public function validate(NodeInterface $comprobante, Asserts $asserts)
    {
        $asserts->put(
            'TFDVERSION01',
            'Si existe el complemento timbre fiscal digital, entonces su versión debe ser 1.1'
        );

        $tfdVersion = $comprobante->searchNode('cfdi:Complemento', 'tfd:TimbreFiscalDigital');
        if (null !== $tfdVersion) {
            $asserts->putStatus(
                'TFDVERSION01',
                Status::when('1.1' === $tfdVersion['Version'])
            );
        }
    }
}
