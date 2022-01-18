<?php

namespace CfdiUtils\Elements\Cfdi40;

class ConceptoImpuestos extends Impuestos
{
    protected function getElementImpuestos(): Impuestos
    {
        return $this;
    }

    public function getChildrenOrder(): array
    {
        return [
            'cfdi:Traslados',
            'cfdi:Retenciones',
        ];
    }
}
