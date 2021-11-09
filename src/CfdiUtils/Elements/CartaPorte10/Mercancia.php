<?php

namespace CfdiUtils\Elements\CartaPorte10;

use CfdiUtils\Elements\Common\AbstractElement;

class Mercancia extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cartaporte:Mercancia';
    }

    public function addCantidadTransporta(array $attributes = []): CantidadTransporta
    {
        $cantidadTransporta = new CantidadTransporta($attributes);
        $this->addChild($cantidadTransporta);

        return $cantidadTransporta;
    }

    public function multiCantidadTransporta(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addCantidadTransporta($attributes);
        }
        return $this;
    }

    public function addDetalleMercancia(array $attributes = []): DetalleMercancia
    {
        $detalleMercancia = new DetalleMercancia($attributes);
        $this->addChild($detalleMercancia);

        return $detalleMercancia;
    }

    public function getDetalleMercancia(): DetalleMercancia
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->helperGetOrAdd(new DetalleMercancia());
    }
}
