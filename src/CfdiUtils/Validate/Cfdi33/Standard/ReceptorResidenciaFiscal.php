<?php

namespace CfdiUtils\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\Node;
use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Cfdi33\Abstracts\AbstractDiscoverableVersion33;
use CfdiUtils\Validate\Status;

/**
 * ReceptorResidenciaFiscal
 *
 * Valida que:
 * - RESFISC01: Si el RFC no es XEXX010101000, entonces la residencia fiscal no debe existir (CFDI33134)
 * - RESFISC02: Si el RFC sí es XEXX010101000 y existe el complemento de comercio exterior,
 *              entonces la residencia fiscal debe establecerse y no puede ser "MEX" (CFDI33135 y CFDI33136)
 * - RESFISC03: Si el RFC sí es XEXX010101000 y se registró el número de registro de identificación fiscal,
 *              entonces la residencia fiscal debe establecerse y no puede ser "MEX" (CFDI33135 y CFDI33136)
 */
class ReceptorResidenciaFiscal extends AbstractDiscoverableVersion33
{
    private function registerAsserts(Asserts $asserts)
    {
        $assertDescriptions = [
            'RESFISC01' => 'Si el RFC no es XEXX010101000, entonces la residencia fiscal no debe existir (CFDI33134)',
            'RESFISC02' => 'Si el RFC sí es XEXX010101000 y existe el complemento de comercio exterior,'
                . ' entonces la residencia fiscal debe establecerse y no puede ser "MEX" (CFDI33135 y CFDI33136)',
            'RESFISC03' => 'Si el RFC sí es XEXX010101000 y se registró el número de registro de identificación fiscal,'
                . ' entonces la residencia fiscal debe establecerse y no puede ser "MEX" (CFDI33135 y CFDI33136)',
        ];
        foreach ($assertDescriptions as $code => $title) {
            $asserts->put($code, $title);
        }
    }

    public function validate(NodeInterface $comprobante, Asserts $asserts)
    {
        $this->registerAsserts($asserts);

        $receptor = $comprobante->searchNode('cfdi:Receptor');
        if (null === $receptor) {
            $receptor = new Node('cfdi:Receptor');
        }

        if ('XEXX010101000' !== $receptor['Rfc']) {
            $asserts->putStatus(
                'RESFISC01',
                Status::when(! $receptor->offsetExists('ResidenciaFiscal'))
            );
            return;
        }

        $existsComercioExterior = (null !== $comprobante->searchNode('cfdi:Complemento', 'cce11:ComercioExterior'));
        $isValidResidenciaFiscal = '' !== $receptor['ResidenciaFiscal'] && 'MEX' !== $receptor['ResidenciaFiscal'];
        if ($existsComercioExterior) {
            $asserts->putStatus(
                'RESFISC02',
                Status::when($isValidResidenciaFiscal)
            );
        }
        if ($receptor->offsetExists('NumRegIdTrib')) {
            $asserts->putStatus(
                'RESFISC03',
                Status::when($isValidResidenciaFiscal)
            );
        }
    }
}
