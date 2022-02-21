<?php

namespace CfdiUtils\Elements\Cfdi40;

use CfdiUtils\Elements\Common\AbstractElement;

class CfdiRelacionados extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cfdi:CfdiRelacionados';
    }

    public function addCfdiRelacionado(array $attributes = []): CfdiRelacionado
    {
        $subject = new CfdiRelacionado($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiCfdiRelacionado(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addCfdiRelacionado($attributes);
        }
        return $this;
    }
}
