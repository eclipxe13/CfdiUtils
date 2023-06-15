<?php

namespace CfdiUtils\Elements\ConsumoDeCombustibles11;

use CfdiUtils\Elements\Common\AbstractElement;

class Determinados extends AbstractElement
{
    public function getElementName(): string
    {
        return 'consumodecombustibles11:Determinados';
    }

    public function addDeterminado(array $attributes = []): Determinado
    {
        $subject = new Determinado($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiDeterminado(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addDeterminado($attributes);
        }
        return $this;
    }
}
