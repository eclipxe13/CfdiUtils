<?php

namespace CfdiUtils\Elements\Nomina12;

use CfdiUtils\Elements\Common\AbstractElement;

class OtrosPagos extends AbstractElement
{
    public function getElementName(): string
    {
        return 'nomina12:OtrosPagos';
    }

    public function addOtrosPago(array $attributes = []): OtroPago
    {
        $deduccion = new OtroPago($attributes);
        $this->addChild($deduccion);
        return $deduccion;
    }

    public function multiOtrosPago(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addOtrosPago($attributes);
        }
        return $this;
    }
}
