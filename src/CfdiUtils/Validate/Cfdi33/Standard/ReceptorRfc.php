<?php

namespace CfdiUtils\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Utils\Rfc;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Cfdi33\Abstracts\AbstractDiscoverableVersion33;
use CfdiUtils\Validate\Status;

/**
 * ReceptorRfc
 *
 * Valida que:
 *  - RECRFC01: El RFC del receptor del comprobante debe ser válido
 */
class ReceptorRfc extends AbstractDiscoverableVersion33
{
    public function validate(NodeInterface $comprobante, Asserts $asserts)
    {
        $assert = $asserts->put('RECRFC01', 'El RFC del receptor del comprobante debe ser válido');

        $receptorRfc = $comprobante->searchAttribute('cfdi:Receptor', 'Rfc');

        try {
            Rfc::checkIsValid($receptorRfc);
        } catch (\Exception $exception) {
            $assert->setStatus(
                Status::error(),
                sprintf('Rfc: "%s". %s', $receptorRfc, $exception->getMessage())
            );
            return;
        }
        $assert->setStatus(Status::ok());
    }
}
