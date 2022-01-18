<?php

namespace CfdiUtils\Elements\Cfdi40;

use CfdiUtils\Elements\Common\AbstractElement;
use CfdiUtils\Nodes\NodeInterface;

class Concepto extends AbstractElement
{
    use Traits\ImpuestosTrait;

    protected function getElementImpuestos(): Impuestos
    {
        return $this->getImpuestos();
    }

    public function getElementName(): string
    {
        return 'cfdi:Concepto';
    }

    public function getChildrenOrder(): array
    {
        return [
            'cfdi:Impuestos',
            'cfdi:ACuentaTerceros',
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

    public function addImpuestos(array $attributes = []): ConceptoImpuestos
    {
        $subject = $this->getImpuestos();
        $subject->addAttributes($attributes);
        return $subject;
    }

    public function getACuentaTerceros(): ACuentaTerceros
    {
        return $this->helperGetOrAdd(new ACuentaTerceros());
    }

    public function addACuentaTerceros(array $attributes = []): ACuentaTerceros
    {
        $subject = $this->getACuentaTerceros();
        $subject->addAttributes($attributes);
        return $subject;
    }

    public function addInformacionAduanera(array $attributes = []): InformacionAduanera
    {
        $subject = new InformacionAduanera($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiInformacionAduanera(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addInformacionAduanera($attributes);
        }
        return $this;
    }

    public function addCuentaPredial(array $attributes = []): CuentaPredial
    {
        $subject = new CuentaPredial($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiCuentaPredial(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addCuentaPredial($attributes);
        }
        return $this;
    }

    public function getComplementoConcepto(): ComplementoConcepto
    {
        return $this->helperGetOrAdd(new ComplementoConcepto());
    }

    public function addComplementoConcepto(NodeInterface $child): self
    {
        $this->getComplementoConcepto()->addChild($child);
        return $this;
    }

    public function addParte(array $attributes = []): Parte
    {
        $subject = new Parte($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiParte(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addParte($attributes);
        }
        return $this;
    }
}
