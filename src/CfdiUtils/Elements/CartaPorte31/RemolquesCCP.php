<?php

namespace CfdiUtils\Elements\CartaPorte31;

use CfdiUtils\Elements\Common\AbstractElement;

class RemolquesCCP extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cartaporte31:RemolquesCCP';
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
