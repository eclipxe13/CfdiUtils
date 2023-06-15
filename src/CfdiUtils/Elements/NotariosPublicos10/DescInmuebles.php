<?php

namespace CfdiUtils\Elements\NotariosPublicos10;

use CfdiUtils\Elements\Common\AbstractElement;

class DescInmuebles extends AbstractElement
{
    public function getElementName(): string
    {
        return 'notariospublicos:DescInmuebles';
    }

    public function addDescInmueble(array $attributes = []): DescInmueble
    {
        $subject = new DescInmueble($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiDescInmueble(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addDescInmueble($attributes);
        }
        return $this;
    }
}
