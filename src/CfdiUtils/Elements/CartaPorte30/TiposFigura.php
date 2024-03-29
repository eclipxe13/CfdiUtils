<?php

namespace CfdiUtils\Elements\CartaPorte30;

use CfdiUtils\Elements\Common\AbstractElement;

class TiposFigura extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cartaporte30:TiposFigura';
    }

    public function getChildrenOrder(): array
    {
        return [
            'cartaporte30:PartesTransporte',
            'cartaporte30:Domicilio',
        ];
    }

    public function addPartesTransporte(array $attributes = []): PartesTransporte
    {
        $subject = new PartesTransporte($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiPartesTransporte(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addPartesTransporte($attributes);
        }
        return $this;
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
