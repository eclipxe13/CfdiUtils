<?php

namespace CfdiUtils\Elements\CartaPorte10;

use CfdiUtils\Elements\Common\AbstractElement;

class Ubicacion extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cartaporte:Ubicacion';
    }

    public function getChildrenOrder(): array
    {
        return ['cartaporte:Origen', 'cartaporte:Destino', 'cartaporte:Domicilio'];
    }

    public function getOrigen(): Origen
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->helperGetOrAdd(new Origen());
    }

    public function addOrigen(array $attributes = []): Origen
    {
        $origen = $this->getOrigen();
        $origen->addAttributes($attributes);

        return $origen;
    }

    public function getDestino(): Destino
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->helperGetOrAdd(new Destino());
    }

    public function addDestino(array $attributes = []): Destino
    {
        $destino = $this->getDestino();
        $destino->addAttributes($attributes);

        return $destino;
    }

    public function getDomicilio(): Domicilio
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->helperGetOrAdd(new Domicilio());
    }

    public function addDomicilio(array $attributes = []): Domicilio
    {
        $domicilio = $this->getDomicilio();
        $domicilio->addAttributes($attributes);

        return $domicilio;
    }
}
