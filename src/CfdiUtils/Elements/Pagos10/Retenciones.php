<?php

namespace CfdiUtils\Elements\Pagos10;

use CfdiUtils\Elements\Common\AbstractElement;

class Retenciones extends AbstractElement
{
    public function getElementName(): string
    {
        return 'pago10:Retenciones';
    }

    public function addRetencion(array $attributes = []): Retencion
    {
        $retencion = new Retencion($attributes);
        $this->addChild($retencion);
        return $retencion;
    }

    public function multiRetencion(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addRetencion($attributes);
        }
        return $this;
    }
}
