<?php
namespace CfdiUtils\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Cfdi33\Abstracts\AbstractDiscoverableVersion33;
use CfdiUtils\Validate\Status;

/**
 * EmisorRegimenFiscal
 *
 * Valida que:
 *  - REGFIS01: El régimen fiscal contenga un valor apropiado según el tipo de RFC emisor (CFDI33130 y CFDI33131)
 */
class EmisorRegimenFiscal extends AbstractDiscoverableVersion33
{
    public function validate(NodeInterface $comprobante, Asserts $asserts)
    {
        $regimenFiscal = $comprobante->searchAttribute('cfdi:Emisor', 'RegimenFiscal');
        $emisorRfc = $comprobante->searchAttribute('cfdi:Emisor', 'Rfc');

        $validMoralCodes = [
            '601', '603', '609', '620', '623', '624', '628', '607', '610', '622',
        ];
        $validFisicaCodes = [
            '605', '606', '608', '611', '612', '614', '616', '621', '629', '630', '615', '610', '622',
        ];

        $length = strlen($emisorRfc);
        $validation = ($length === 12 && in_array($regimenFiscal, $validMoralCodes, true))
            || ($length === 13 && in_array($regimenFiscal, $validFisicaCodes, true));

        $asserts->put(
            'REGFIS01',
            'El régimen fiscal contenga un valor apropiado según el tipo de RFC emisor (CFDI33130 y CFDI33131)',
            Status::when($validation),
            sprintf('Rfc: "%s", Regimen Fiscal: "%s"', $emisorRfc, $regimenFiscal)
        );
    }
}
