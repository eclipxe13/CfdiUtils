<?php

namespace CfdiUtils\Elements\CartaPorte31;

use CfdiUtils\Elements\Common\AbstractElement;

class Ubicacion extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cartaporte31:Ubicacion';
    }

    public function getDomicilio(): Domicilio
    {
        return $this->helperGetOrAdd(new Domicilio());
    }

    public function addDomicilio(array $attributes = []): Domicilio
    {
        $subject = $this->getDomicilio();
        $subject->addAttributes($attributes);
        return $subject;
    }
}
