<?php

namespace CfdiUtils\Elements\CartaPorte10;

use CfdiUtils\Elements\Common\AbstractElement;

class Ubicaciones extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cartaporte:Ubicaciones';
    }

    public function addUbicacion(array $attributes = []): Ubicacion
    {
        $ubicacion = new Ubicacion($attributes);
        $this->addChild($ubicacion);
        return $ubicacion;
    }

    public function multiUbicacion(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addUbicacion($attributes);
        }
        return $this;
    }
}
