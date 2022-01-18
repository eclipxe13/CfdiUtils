<?php

namespace CfdiUtils\Elements\Cfdi40;

use CfdiUtils\Elements\Common\AbstractElement;
use CfdiUtils\Nodes\NodeInterface;

class Comprobante extends AbstractElement
{
    use Traits\ImpuestosTrait;

    protected function getElementImpuestos(): Impuestos
    {
        return $this->getImpuestos();
    }

    public function getElementName(): string
    {
        return 'cfdi:Comprobante';
    }

    public function getChildrenOrder(): array
    {
        return [
            'cfdi:InformacionGlobal',
            'cfdi:CfdiRelacionados',
            'cfdi:Emisor',
            'cfdi:Receptor',
            'cfdi:Conceptos',
            'cfdi:Impuestos',
            'cfdi:Complemento',
            'cfdi:Addenda',
        ];
    }

    public function getFixedAttributes(): array
    {
        return [
            'xmlns:cfdi' => 'http://www.sat.gob.mx/cfd/4',
            'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/cfd/4'
                . ' http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd',
            'Version' => '4.0',
        ];
    }

    public function getInformacionGlobal(): InformacionGlobal
    {
        return $this->helperGetOrAdd(new InformacionGlobal());
    }

    public function addInformacionGlobal(array $attributes = []): InformacionGlobal
    {
        $subject = $this->getInformacionGlobal();
        $subject->addAttributes($attributes);
        return $subject;
    }

    public function addCfdiRelacionados(array $attributes = []): CfdiRelacionados
    {
        $subject = new CfdiRelacionados($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiCfdiRelacionados(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addCfdiRelacionados($attributes);
        }
        return $this;
    }

    public function getEmisor(): Emisor
    {
        return $this->helperGetOrAdd(new Emisor());
    }

    public function addEmisor(array $attributes = []): Emisor
    {
        $subject = $this->getEmisor();
        $subject->addAttributes($attributes);
        return $subject;
    }

    public function getReceptor(): Receptor
    {
        return $this->helperGetOrAdd(new Receptor());
    }

    public function addReceptor(array $attributes = []): Receptor
    {
        $subject = $this->getReceptor();
        $subject->addAttributes($attributes);
        return $subject;
    }

    public function getConceptos(): Conceptos
    {
        return $this->helperGetOrAdd(new Conceptos());
    }

    public function addConceptos(array $attributes = []): Conceptos
    {
        $subject = $this->getConceptos();
        $subject->addAttributes($attributes);
        return $subject;
    }

    public function getImpuestos(): Impuestos
    {
        return $this->helperGetOrAdd(new Impuestos());
    }

    public function addImpuestos(array $attributes = []): Impuestos
    {
        $subject = $this->getImpuestos();
        $subject->addAttributes($attributes);
        return $subject;
    }

    public function getComplemento(): Complemento
    {
        return $this->helperGetOrAdd(new Complemento());
    }

    public function addComplemento(NodeInterface $children): self
    {
        $this->getComplemento()->addChild($children);
        return $this;
    }

    public function getAddenda(): Addenda
    {
        return $this->helperGetOrAdd(new Addenda());
    }

    public function addAddenda(NodeInterface $children): self
    {
        $this->getAddenda()->addChild($children);
        return $this;
    }

    public function addConcepto(array $attributes = []): Concepto
    {
        return $this->getConceptos()->addConcepto($attributes);
    }
}
