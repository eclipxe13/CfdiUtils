<?php

namespace CfdiUtils\Elements\Retenciones20;

use CfdiUtils\Elements\Common\AbstractElement;

class Totales extends AbstractElement
{
    public function getElementName(): string
    {
        return 'retenciones:Totales';
    }

    public function addImpRetenidos(array $attributes = [], array $children = []): ImpRetenidos
    {
        $impRetenidos = new ImpRetenidos($attributes, $children);
        $this->addChild($impRetenidos);
        return $impRetenidos;
    }

    public function multiImpRetenidos(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addImpRetenidos($attributes);
        }
        return $this;
    }
}
