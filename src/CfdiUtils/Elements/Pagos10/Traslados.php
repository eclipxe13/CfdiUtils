<?php

namespace CfdiUtils\Elements\Pagos10;

use CfdiUtils\Elements\Common\AbstractElement;

class Traslados extends AbstractElement
{
    public function getElementName(): string
    {
        return 'pago10:Traslados';
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
