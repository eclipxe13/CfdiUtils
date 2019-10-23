<?php

namespace CfdiUtils\Elements\Cfdi33;

use CfdiUtils\Elements\Common\AbstractElement;

class Traslados extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cfdi:Traslados';
    }

    public function addTraslado(array $attributes = []): Traslado
    {
        $traslado = new Traslado($attributes);
        $this->addChild($traslado);
        return $traslado;
    }

    public function multiTraslado(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addTraslado($attributes);
        }
        return $this;
    }
}
