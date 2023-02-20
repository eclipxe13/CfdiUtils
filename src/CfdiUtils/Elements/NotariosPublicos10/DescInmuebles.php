<?php

namespace CfdiUtils\Elements\NotariosPublicos10;

use CfdiUtils\Elements\Common\AbstractElement;

class DescInmuebles extends AbstractElement
{
    public function getElementName(): string
    {
        return 'notariospublicos:DescInmuebles';
    }

    public function getDescInmueble(): DescInmueble
    {
        return $this->helperGetOrAdd(new DescInmueble());
    }

    public function addDescInmueble(array $attributes = []): DescInmueble
    {
        $subject = $this->getDescInmueble();
        $subject->addAttributes($attributes);
        return $subject;
    }
}
