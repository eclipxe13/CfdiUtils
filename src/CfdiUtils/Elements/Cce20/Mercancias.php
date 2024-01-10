<?php

namespace CfdiUtils\Elements\Cce20;

use CfdiUtils\Elements\Common\AbstractElement;

class Mercancias extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cce20:Mercancias';
    }

    public function addMercancia(array $attributes = []): Mercancia
    {
        $subject = new Mercancia($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiMercancia(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addMercancia($attributes);
        }
        return $this;
    }
}
