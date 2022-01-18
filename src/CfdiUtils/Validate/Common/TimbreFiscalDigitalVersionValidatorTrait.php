<?php

namespace CfdiUtils\Validate\Common;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Status;

trait TimbreFiscalDigitalVersionValidatorTrait
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
