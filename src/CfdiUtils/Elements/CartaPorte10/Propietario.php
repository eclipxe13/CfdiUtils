<?php

namespace CfdiUtils\Elements\CartaPorte10;

use CfdiUtils\Elements\Common\AbstractElement;

class Propietario extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cartaporte:Propietario';
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
