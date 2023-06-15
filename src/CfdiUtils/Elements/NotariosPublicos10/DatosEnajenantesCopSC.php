<?php

namespace CfdiUtils\Elements\NotariosPublicos10;

use CfdiUtils\Elements\Common\AbstractElement;

class DatosEnajenantesCopSC extends AbstractElement
{
    public function getElementName(): string
    {
        return 'notariospublicos:DatosEnajenantesCopSC';
    }

    public function addDatosEnajenanteCopSC(array $attributes = []): DatosEnajenanteCopSC
    {
        $subject = new DatosEnajenanteCopSC($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiDatosEnajenanteCopSC(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addDatosEnajenanteCopSC($attributes);
        }
        return $this;
    }
}
