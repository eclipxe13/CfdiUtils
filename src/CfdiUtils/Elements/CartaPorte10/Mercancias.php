<?php

namespace CfdiUtils\Elements\CartaPorte10;

use CfdiUtils\Elements\Common\AbstractElement;

class Mercancias extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cartaporte:Mercancias';
    }

    public function addMercancia(array $attributes = []): Mercancia
    {
        $ubicacion = new Mercancia($attributes);
        $this->addChild($ubicacion);
        return $ubicacion;
    }

    public function multiMercancia(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addMercancia($attributes);
        }
        return $this;
    }
}
