<?php

namespace CfdiUtils\Elements\CartaPorte10;

use CfdiUtils\Elements\Common\AbstractElement;

class Operadores extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cartaporte:Operadores';
    }

    public function addOperador(array $attributes = []): Operador
    {
        $operador = new Operador($attributes);
        $this->addChild($operador);

        return $operador;
    }

    public function multiOperador(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addOperador($attributes);
        }
        return $this;
    }
}
