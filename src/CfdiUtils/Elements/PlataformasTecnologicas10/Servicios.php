<?php

namespace CfdiUtils\Elements\PlataformasTecnologicas10;

use CfdiUtils\Elements\Common\AbstractElement;

class Servicios extends AbstractElement
{
    public function getElementName(): string
    {
        return 'plataformasTecnologicas:Servicios';
    }

    public function addDetallesDelServicio(array $attributes = []): DetallesDelServicio
    {
        $subject = new DetallesDelServicio($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiDetallesDelServicio(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addDetallesDelServicio($attributes);
        }
        return $this;
    }
}
