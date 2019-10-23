<?php

namespace CfdiUtils\Elements\Cfdi33;

use CfdiUtils\Elements\Common\AbstractElement;

class CfdiRelacionados extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cfdi:CfdiRelacionados';
    }

    public function addCfdiRelacionado(array $attributes = []): CfdiRelacionado
    {
        $cfdiRelacionado = new CfdiRelacionado($attributes);
        $this->addChild($cfdiRelacionado);
        return $cfdiRelacionado;
    }

    public function multiCfdiRelacionado(array $elementAttributes = []): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addCfdiRelacionado($attributes);
        }
        return $this;
    }
}
