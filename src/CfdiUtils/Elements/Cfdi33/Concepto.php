<?php

namespace CfdiUtils\Elements\Cfdi33;

use CfdiUtils\Elements\Cfdi33\Traits\ImpuestosTrait;
use CfdiUtils\Elements\Cfdi33\Traits\InformacionAduaneraTrait;
use CfdiUtils\Elements\Common\AbstractElement;

class Concepto extends AbstractElement
{
    use ImpuestosTrait;
    use InformacionAduaneraTrait;

    public function getElementName(): string
    {
        return 'cfdi:Concepto';
    }

    public function getChildrenOrder(): array
    {
        return [
            'cfdi:Impuestos',
            'cfdi:InformacionAduanera',
            'cfdi:CuentaPredial',
            'cfdi:ComplementoConcepto',
            'cfdi:Parte',
        ];
    }

    public function getImpuestos(): ConceptoImpuestos
    {
        return $this->helperGetOrAdd(new ConceptoImpuestos());
    }

    public function getCuentaPredial(): CuentaPredial
    {
        return $this->helperGetOrAdd(new CuentaPredial());
    }

    public function addCuentaPredial(array $attributes = []): CuentaPredial
    {
        $cuentaPredial = $this->getCuentaPredial();
        $cuentaPredial->addAttributes($attributes);
        return $cuentaPredial;
    }

    public function getComplementoConcepto(): ComplementoConcepto
    {
        return $this->helperGetOrAdd(new ComplementoConcepto());
    }

    public function addComplementoConcepto(array $attributes = [], array $children = []): ComplementoConcepto
    {
        $complementoConcepto = $this->getComplementoConcepto();
        $complementoConcepto->addAttributes($attributes);
        $complementoConcepto->children()->importFromArray($children);
        return $complementoConcepto;
    }

    public function addParte(array $attributes = [], array $children = []): Parte
    {
        $parte = new Parte($attributes, $children);
        $this->addChild($parte);
        return $parte;
    }

    public function multiParte(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addParte($attributes);
        }
        return $this;
    }
}
