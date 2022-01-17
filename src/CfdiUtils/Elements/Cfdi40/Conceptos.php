<?php

namespace CfdiUtils\Elements\Cfdi40;

use CfdiUtils\Elements\Common\AbstractElement;

class Conceptos extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cfdi:Conceptos';
    }

    public function addConcepto(array $attributes = []): Concepto
    {
        $subject = new Concepto($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiConcepto(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addConcepto($attributes);
        }
        return $this;
    }
}
