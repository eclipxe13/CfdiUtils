<?php

namespace CfdiUtils\Elements\NotariosPublicos10;

use CfdiUtils\Elements\Common\AbstractElement;

class DatosAdquirientesCopSC extends AbstractElement
{
    public function getElementName(): string
    {
        return 'notariospublicos:DatosAdquirientesCopSC';
    }

    public function addDatosAdquirienteCopSC(array $attributes = []): DatosAdquirienteCopSC
    {
        $subject = new DatosAdquirienteCopSC($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiDatosAdquirienteCopSC(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addDatosAdquirienteCopSC($attributes);
        }
        return $this;
    }
}
