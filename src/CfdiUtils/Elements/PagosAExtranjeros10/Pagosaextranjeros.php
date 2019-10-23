<?php

namespace CfdiUtils\Elements\PagosAExtranjeros10;

use CfdiUtils\Elements\Common\AbstractElement;

class Pagosaextranjeros extends AbstractElement
{
    public function getElementName(): string
    {
        return 'pagosaextranjeros:Pagosaextranjeros';
    }

    public function getNoBeneficiario(): NoBeneficiario
    {
        return $this->helperGetOrAdd(new NoBeneficiario());
    }

    public function addNoBeneficiario(array $attributes = []): NoBeneficiario
    {
        $noBeneficiario = $this->getNoBeneficiario();
        $noBeneficiario->addAttributes($attributes);
        return $noBeneficiario;
    }

    public function getBeneficiario(): Beneficiario
    {
        return $this->helperGetOrAdd(new Beneficiario());
    }

    public function addBeneficiario(array $attributes = []): Beneficiario
    {
        $beneficiario = $this->getBeneficiario();
        $beneficiario->addAttributes($attributes);
        return $beneficiario;
    }

    public function getChildrenOrder(): array
    {
        return [
            'pagosaextranjeros:NoBeneficiario',
            'pagosaextranjeros:Beneficiario',
        ];
    }

    public function getFixedAttributes(): array
    {
        return [
            'xmlns:pagosaextranjeros' => 'http://www.sat.gob.mx/esquemas/retencionpago/1/pagosaextranjeros',
            'xsi:schemaLocation' => vsprintf('%s %s', [
                'http://www.sat.gob.mx/esquemas/retencionpago/1/pagosaextranjeros',
                'http://www.sat.gob.mx/esquemas/retencionpago/1/pagosaextranjeros/pagosaextranjeros.xsd',
            ]),
            'Version' => '1.0',
        ];
    }
}
