<?php

namespace CfdiUtils\Elements\Cfdi40;

use CfdiUtils\Elements\Common\AbstractElement;

class Impuestos extends AbstractElement
{
    use Traits\ImpuestosTrait;

    protected function getElementImpuestos(): self
    {
        return $this;
    }

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

    public function addTraslados(array $attributes = []): Traslados
    {
        $subject = $this->getTraslados();
        $subject->addAttributes($attributes);
        return $subject;
    }

    public function getRetenciones(): Retenciones
    {
        return $this->helperGetOrAdd(new Retenciones());
    }

    public function addRetenciones(array $attributes = []): Retenciones
    {
        $subject = $this->getRetenciones();
        $subject->addAttributes($attributes);
        return $subject;
    }
}
