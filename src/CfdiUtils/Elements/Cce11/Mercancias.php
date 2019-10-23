<?php

namespace CfdiUtils\Elements\Cce11;

use CfdiUtils\Elements\Common\AbstractElement;

class Mercancias extends AbstractElement
{
    public function addMercancia(array $attributes = []): Mercancia
    {
        $mercancia = new Mercancia($attributes);
        $this->addChild($mercancia);
        return $mercancia;
    }

    public function getElementName(): string
    {
        return 'cce11:Mercancias';
    }
}
