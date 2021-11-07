<?php

namespace CfdiUtils\Elements\CartaPorte10;

use CfdiUtils\Elements\Common\AbstractElement;

class TransporteMaritimo extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cartaporte:TransporteMaritimo';
    }

    public function addContenedor(array $attributes = []): Contenedor
    {
        $contenedores = new Contenedor($attributes);
        $this->addChild($contenedores);
        return $contenedores;
    }

    public function multiContenedor(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addContenedor($attributes);
        }
        return $this;
    }
}
