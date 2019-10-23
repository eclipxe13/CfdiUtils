<?php

namespace CfdiUtils\Elements\Cfdi33;

use CfdiUtils\Elements\Common\AbstractElement;

class Impuestos extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cfdi:Impuestos';
    }

    public function getChildrenOrder(): array
    {
        return [
            'cfdi:Retenciones',
            'cfdi:Traslados',
        ];
    }

    public function getTraslados(): Traslados
    {
        return $this->helperGetOrAdd(new Traslados());
    }

    public function getRetenciones(): Retenciones
    {
        return $this->helperGetOrAdd(new Retenciones());
    }
}
