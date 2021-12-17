<?php

namespace CfdiUtils\Elements\CartaPorte20;

use CfdiUtils\Elements\Common\AbstractElement;

class FiguraTransporte extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cartaporte20:FiguraTransporte';
    }

    public function addTiposFigura(array $attributes = []): TiposFigura
    {
        $subject = new TiposFigura($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiTiposFigura(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addTiposFigura($attributes);
        }
        return $this;
    }
}
