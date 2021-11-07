<?php

namespace CfdiUtils\Elements\CartaPorte10;

use CfdiUtils\Elements\Common\AbstractElement;

class Carro extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cartaporte:Carro';
    }

    public function addContenedor(array $attributes = []): Contenedor
    {
        $contenedor = new Contenedor($attributes);
        $this->addChild($contenedor);

        return $contenedor;
    }

    public function multiContenedor(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addContenedor($attributes);
        }
        return $this;
    }
}
