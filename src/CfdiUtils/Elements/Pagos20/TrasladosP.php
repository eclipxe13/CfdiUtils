<?php

namespace CfdiUtils\Elements\Pagos20;

use CfdiUtils\Elements\Common\AbstractElement;

class TrasladosP extends AbstractElement
{
    public function getElementName(): string
    {
        return 'pago20:TrasladosP';
    }

    public function addTrasladoP(array $attributes = []): TrasladoP
    {
        $subject = new TrasladoP($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiTrasladoP(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addTrasladoP($attributes);
        }
        return $this;
    }
}
