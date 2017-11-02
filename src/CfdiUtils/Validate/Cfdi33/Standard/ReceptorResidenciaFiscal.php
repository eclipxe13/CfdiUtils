<?php
namespace CfdiUtils\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\Node;
use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Cfdi33\Abstracts\AbstractDiscoverableVersion33;
use CfdiUtils\Validate\Status;

/**
 * ComprobanteResidenciaFiscal
 *
 * Valida que:
 * - RESFISC01: Si el RFC no es XEXX010101000 entonces la residencia fiscal no debe existir
 * - RESFISC02: Si el RFC sí es XEXX010101000 y existe el complemento de comercio exterior
 *              entonces la residencia fiscal debe establecerse y no puede ser "MEX"
 * - RESFISC03: Si el RFC sí es XEXX010101000 y se registró el número de registro de identificación fiscal
 *              entonces la residencia fiscal debe establecerse y no puede ser "MEX"
 */
class ReceptorResidenciaFiscal extends AbstractDiscoverableVersion33
{
    private function registerAsserts(Asserts $asserts)
    {
        $assertDescriptions = [
            'RESFISC01' => 'Si el RFC no es XEXX010101000 entonces la residencia fiscal no debe existir',
            'RESFISC02' => 'Si el RFC sí es XEXX010101000 y existe el complemento de comercio exterior'
                        . ' entonces la residencia fiscal debe establecerse y no puede ser "MEX"',
            'RESFISC03' => 'Si el RFC sí es XEXX010101000 y se registró el número de registro de identificación fiscal'
                         . ' entonces la residencia fiscal debe establecerse y no puede ser "MEX"',
        ];
        foreach ($assertDescriptions as $code => $title) {
            $asserts->put($code, $title);
        }
    }
    public function validate(NodeInterface $comprobante, Asserts $asserts)
    {
        $this->registerAsserts($asserts);

        $receptor = $comprobante->searchNode('cfdi:Receptor');
        if ($receptor === null) {
            $receptor = new Node('cfdi:Receptor');
        }

        if ('XEXX010101000' !== $receptor['Rfc']) {
            $asserts->putStatus(
                'RESFISC01',
                Status::when(! isset($receptor['ResidenciaFiscal']))
            );
            return;
        }

        $existsComercioExterior = (null !== $comprobante->searchNode('cfdi:Complemento', 'cce11:ComercioExterior'));
        $isValidResidenciaFiscal = '' !== $receptor['ResidenciaFiscal'] && $receptor['ResidenciaFiscal'] !== 'MEX';
        if ($existsComercioExterior) {
            $asserts->putStatus(
                'RESFISC02',
                Status::when($isValidResidenciaFiscal)
            );
        }
        if (isset($receptor['NumRegIdTrib'])) {
            $asserts->putStatus(
                'RESFISC03',
                Status::when($isValidResidenciaFiscal)
            );
        }
    }
}
