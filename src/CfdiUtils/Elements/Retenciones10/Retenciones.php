<?php

namespace CfdiUtils\Elements\Retenciones10;

use CfdiUtils\Elements\Common\AbstractElement;
use CfdiUtils\Nodes\NodeInterface;

class Retenciones extends AbstractElement
{
    public function getElementName(): string
    {
        return 'retenciones:Retenciones';
    }

    public function getEmisor(): Emisor
    {
        return $this->helperGetOrAdd(new Emisor());
    }

    public function addEmisor(array $attributes = []): Emisor
    {
        $emisor = $this->getEmisor();
        $emisor->addAttributes($attributes);
        return $emisor;
    }

    public function getReceptor(): Receptor
    {
        return $this->helperGetOrAdd(new Receptor());
    }

    public function addReceptor(array $attributes = []): Receptor
    {
        $receptor = $this->getReceptor();
        $receptor->addAttributes($attributes);
        return $receptor;
    }

    public function getPeriodo(): Periodo
    {
        return $this->helperGetOrAdd(new Periodo());
    }

    public function addPeriodo(array $attributes = []): Periodo
    {
        $periodo = $this->getPeriodo();
        $periodo->addAttributes($attributes);
        return $periodo;
    }

    public function getTotales(): Totales
    {
        return $this->helperGetOrAdd(new Totales());
    }

    public function addTotales(array $attributes = []): Totales
    {
        $totales = $this->getTotales();
        $totales->addAttributes($attributes);
        return $totales;
    }

    public function addImpRetenidos(array $attributes = []): ImpRetenidos
    {
        return $this->getTotales()->addImpRetenidos($attributes);
    }

    public function multiImpRetenidos(array ...$elementAttributes): self
    {
        $this->getTotales()->multiImpRetenidos(...$elementAttributes);
        return $this;
    }

    public function getComplemento(): Complemento
    {
        return $this->helperGetOrAdd(new Complemento());
    }

    public function addComplemento(NodeInterface $children): self
    {
        $this->getComplemento()->add($children);
        return $this;
    }

    public function getAddenda(): Addenda
    {
        return $this->helperGetOrAdd(new Addenda());
    }

    public function addAddenda(NodeInterface $children): self
    {
        $this->getAddenda()->add($children);
        return $this;
    }

    public function getChildrenOrder(): array
    {
        return [
            'retenciones:Emisor',
            'retenciones:Receptor',
            'retenciones:Periodo',
            'retenciones:Totales',
            'retenciones:Complemento',
            'retenciones:Addenda',
        ];
    }

    public function getFixedAttributes(): array
    {
        return [
            'xmlns:retenciones' => 'http://www.sat.gob.mx/esquemas/retencionpago/1',
            'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:schemaLocation' => vsprintf('%s %s', [
                'http://www.sat.gob.mx/esquemas/retencionpago/1',
                'http://www.sat.gob.mx/esquemas/retencionpago/1/retencionpagov1.xsd',
            ]),
            'Version' => '1.0',
        ];
    }
}
