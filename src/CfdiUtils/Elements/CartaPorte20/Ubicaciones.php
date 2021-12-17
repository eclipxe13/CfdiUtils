<?php

namespace CfdiUtils\Elements\CartaPorte20;

use CfdiUtils\Elements\Common\AbstractElement;

class Ubicaciones extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cartaporte20:Ubicaciones';
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
