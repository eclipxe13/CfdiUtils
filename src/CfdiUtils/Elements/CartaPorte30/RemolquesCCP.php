<?php

namespace CfdiUtils\Elements\CartaPorte30;

use CfdiUtils\Elements\Common\AbstractElement;

class RemolquesCCP extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cartaporte30:RemolquesCCP';
    }

    public function addRemolqueCCP(array $attributes = []): RemolqueCCP
    {
        $subject = new RemolqueCCP($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiRemolqueCCP(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addRemolqueCCP($attributes);
        }
        return $this;
    }
}
