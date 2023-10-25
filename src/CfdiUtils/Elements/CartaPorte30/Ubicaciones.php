<?php

namespace CfdiUtils\Elements\CartaPorte30;

use CfdiUtils\Elements\Common\AbstractElement;

class Ubicaciones extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cartaporte30:Ubicaciones';
    }

    public function addUbicacion(array $attributes = []): Ubicacion
    {
        $subject = new Ubicacion($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiUbicacion(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addUbicacion($attributes);
        }
        return $this;
    }
}
