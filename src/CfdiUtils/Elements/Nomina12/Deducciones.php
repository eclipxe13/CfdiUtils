<?php

namespace CfdiUtils\Elements\Nomina12;

use CfdiUtils\Elements\Common\AbstractElement;

class Deducciones extends AbstractElement
{
    public function getElementName(): string
    {
        return 'nomina12:Deducciones';
    }

    public function addDeduccion(array $attributes = []): Deduccion
    {
        $deduccion = new Deduccion($attributes);
        $this->addChild($deduccion);
        return $deduccion;
    }

    public function multiDeduccion(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addDeduccion($attributes);
        }
        return $this;
    }
}
