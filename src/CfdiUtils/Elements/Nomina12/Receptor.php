<?php

namespace CfdiUtils\Elements\Nomina12;

use CfdiUtils\Elements\Common\AbstractElement;

class Receptor extends AbstractElement
{
    public function getElementName(): string
    {
        return 'nomina12:Receptor';
    }

    public function addSubContratacion(array $attributes = []): SubContratacion
    {
        $subContratacion = new SubContratacion($attributes);
        $this->addChild($subContratacion);
        return $subContratacion;
    }

    public function multiSubContratacion(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addSubContratacion($attributes);
        }
        return $this;
    }
}
