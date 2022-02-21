<?php

namespace CfdiUtils\Elements\Cfdi40;

use CfdiUtils\Elements\Common\AbstractElement;

class Retenciones extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cfdi:Retenciones';
    }

    public function addRetencion(array $attributes = []): Retencion
    {
        $subject = new Retencion($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiRetencion(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addRetencion($attributes);
        }
        return $this;
    }
}
