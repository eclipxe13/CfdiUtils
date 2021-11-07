<?php

namespace CfdiUtils\Elements\CartaPorte10;

use CfdiUtils\Elements\Common\AbstractElement;

class Remolques extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cartaporte:Remolques';
    }

    public function addRemolque(array $attributes = []): Remolque
    {
        $remolque = new Remolque($attributes);
        $this->addChild($remolque);
        return $remolque;
    }

    public function multiRemolque(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addRemolque($attributes);
        }
        return $this;
    }
}
