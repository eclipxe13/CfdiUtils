<?php

namespace CfdiUtils\Elements\Cce11;

use CfdiUtils\Elements\Common\AbstractElement;

class Mercancia extends AbstractElement
{
    public function addDescripcionesEspecificas(array $attributes = []): DescripcionesEspecificas
    {
        $descripcionesEspecificas = new DescripcionesEspecificas($attributes);
        $this->addChild($descripcionesEspecificas);
        return $descripcionesEspecificas;
    }

    public function getElementName(): string
    {
        return 'cce11:Mercancia';
    }
}
