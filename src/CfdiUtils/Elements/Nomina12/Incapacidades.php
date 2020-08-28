<?php

namespace CfdiUtils\Elements\Nomina12;

use CfdiUtils\Elements\Common\AbstractElement;

class Incapacidades extends AbstractElement
{
    public function getElementName(): string
    {
        return 'nomina12:Incapacidades';
    }

    public function addIncapacidad(array $attributes = []): Incapacidad
    {
        $incapacidad = new Incapacidad($attributes);
        $this->addChild($incapacidad);
        return $incapacidad;
    }

    public function multiIncapacidad(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addIncapacidad($attributes);
        }
        return $this;
    }
}
