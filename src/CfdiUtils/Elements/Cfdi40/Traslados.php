<?php

namespace CfdiUtils\Elements\Cfdi40;

use CfdiUtils\Elements\Common\AbstractElement;

class Traslados extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cfdi:Traslados';
    }

    public function addTraslado(array $attributes = []): Traslado
    {
        $subject = new Traslado($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiTraslado(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addTraslado($attributes);
        }
        return $this;
    }
}
