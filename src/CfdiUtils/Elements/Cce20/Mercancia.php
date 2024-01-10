<?php

namespace CfdiUtils\Elements\Cce20;

use CfdiUtils\Elements\Common\AbstractElement;

class Mercancia extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cce20:Mercancia';
    }

    public function addDescripcionesEspecificas(array $attributes = []): DescripcionesEspecificas
    {
        $subject = new DescripcionesEspecificas($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiDescripcionesEspecificas(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addDescripcionesEspecificas($attributes);
        }
        return $this;
    }
}
