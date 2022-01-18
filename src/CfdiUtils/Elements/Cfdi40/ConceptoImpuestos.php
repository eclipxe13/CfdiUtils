<?php

namespace CfdiUtils\Elements\Cfdi40;

class ConceptoImpuestos extends Impuestos
{
    use Traits\ImpuestosTrait;

    protected function getImpuestos(): self
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
