<?php

namespace CfdiUtils\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Utils\Rfc;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Cfdi33\Abstracts\AbstractDiscoverableVersion33;
use CfdiUtils\Validate\Status;

/**
 * EmisorRfc
 *
 * Valida que:
 *  - EMISORRFC01: El RFC del emisor del comprobante debe ser válido y diferente de XAXX010101000 y XEXX010101000
 */
class EmisorRfc extends AbstractDiscoverableVersion33
{
    public function validate(NodeInterface $comprobante, Asserts $asserts)
    {
        $assert = $asserts->put(
            'EMISORRFC01',
            'El RFC del emisor del comprobante debe ser válido y diferente de XAXX010101000 y XEXX010101000'
        );

        $emisorRfc = $comprobante->searchAttribute('cfdi:Emisor', 'Rfc');

        try {
            Rfc::checkIsValid($emisorRfc, Rfc::DISALLOW_GENERIC | Rfc::DISALLOW_FOREIGN);
        } catch (\Exception $exception) {
            $assert->setStatus(
                Status::error(),
                sprintf('Rfc: "%s". %s', $emisorRfc, $exception->getMessage())
            );
            return;
        }
        $assert->setStatus(Status::ok());
    }
}
