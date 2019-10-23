<?php

namespace CfdiUtils\Elements\Cfdi33;

use CfdiUtils\Elements\Cfdi33\Traits\ImpuestosTrait;
use CfdiUtils\Elements\Common\AbstractElement;
use CfdiUtils\Nodes\NodeInterface;

class Comprobante extends AbstractElement
{
    use ImpuestosTrait;

    public function getElementName(): string
    {
        return 'cfdi:Comprobante';
    }

    /**
     * @todo Remove this deprecation error on version 3.0.0
     * @return CfdiRelacionados
     */
    public function getCfdiRelacionados(): CfdiRelacionados
    {
        $arguments = func_get_args();
        if (count($arguments) > 0) {
            trigger_error(
                'El método getCfdiRelacionados ya no admite atributos, use addCfdiRelacionados en su lugar',
                E_USER_NOTICE
            );
            return $this->addCfdiRelacionados($arguments[0]);
        }
        return $this->helperGetOrAdd(new CfdiRelacionados());
    }

    public function addCfdiRelacionados(array $attributes = []): CfdiRelacionados
    {
        $cfdiRelacionados = $this->getCfdiRelacionados();
        $cfdiRelacionados->addAttributes($attributes);
        return $cfdiRelacionados;
    }

    public function addCfdiRelacionado(array $attributes = []): CfdiRelacionado
    {
        return $this->getCfdiRelacionados()->addCfdiRelacionado($attributes);
    }

    public function multiCfdiRelacionado(array ...$elementAttributes): self
    {
        $this->getCfdiRelacionados()->multiCfdiRelacionado($elementAttributes);
        return $this;
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

    public function getConceptos(): Conceptos
    {
        return $this->helperGetOrAdd(new Conceptos());
    }

    public function addConcepto(array $attributes = [], array $children = []): Concepto
    {
        return $this->getConceptos()->addConcepto($attributes, $children);
    }

    public function getImpuestos(): Impuestos
    {
        return $this->helperGetOrAdd(new Impuestos());
    }

    public function addImpuestos(array $attributes = []): Impuestos
    {
        $impuestos = $this->getImpuestos();
        $impuestos->addAttributes($attributes);
        return $impuestos;
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
            'xmlns:cfdi' => 'http://www.sat.gob.mx/cfd/3',
            'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv33.xsd',
            'Version' => '3.3',
        ];
    }
}
