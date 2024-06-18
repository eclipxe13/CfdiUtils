<?php

namespace CfdiUtils\Elements\CartaPorte31;

use CfdiUtils\Elements\CartaPorte31\ContenedorFerroviario as Contenedor;
use CfdiUtils\Elements\Common\AbstractElement;

class Carro extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cartaporte31:Carro';
    }

    public function addContenedor(array $attributes = []): Contenedor
    {
        $subject = new Contenedor($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiContenedor(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addContenedor($attributes);
        }
        return $this;
    }
}
