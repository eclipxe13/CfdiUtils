<?php

namespace CfdiUtils\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Utils\Rfc;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Cfdi33\Abstracts\AbstractDiscoverableVersion33;
use CfdiUtils\Validate\Status;

/**
 * EmisorRegimenFiscal
 *
 * Valida que:
 *  - REGFIS01: El régimen fiscal contenga un valor apropiado según el tipo de RFC emisor (CFDI33130 y CFDI33131)
 *
 * Nota: No valida que el RFC sea válido, esa responsabilidad no es de este validador.
 */
class EmisorRegimenFiscal extends AbstractDiscoverableVersion33
{
    public function validate(NodeInterface $comprobante, Asserts $asserts)
    {
        $regimenFiscal = $comprobante->searchAttribute('cfdi:Emisor', 'RegimenFiscal');
        $emisorRfc = $comprobante->searchAttribute('cfdi:Emisor', 'Rfc');

        $length = mb_strlen($emisorRfc);
        if (12 === $length) {
            $validCodes = [
                '601', '603', '609', '610', '620', '622', '623', '624', '626', '628',
            ];
        } elseif (13 === $length) {
            $validCodes = [
                '605', '606', '607', '608', '610', '611', '612', '614', '615', '616', '621', '625', '626', '629', '630',
            ];
        } else {
            $validCodes = [];
        }

        $asserts->put(
            'REGFIS01',
            'El régimen fiscal contenga un valor apropiado según el tipo de RFC emisor (CFDI33130 y CFDI33131)',
            Status::when(in_array($regimenFiscal, $validCodes, true)),
            sprintf('Rfc: "%s", Regimen Fiscal: "%s"', $emisorRfc, $regimenFiscal)
        );
    }
}
