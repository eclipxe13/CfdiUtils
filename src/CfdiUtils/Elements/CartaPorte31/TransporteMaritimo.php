<?php

namespace CfdiUtils\Elements\CartaPorte31;

use CfdiUtils\Elements\Common\AbstractElement;

class TransporteMaritimo extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cartaporte31:TransporteMaritimo';
    }

    public function getChildrenOrder(): array
    {
        return [
        'cartaporte31:Contenedor',
        'cartaporte31:RemolquesCCP'];
    }

    public function addContenedor(array $attributes = []): Contenedor
    {
        $subject = new Contenedor($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiContenedor(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addContenedor($attributes);
        }
        return $this;
    }

    public function getRemolquesCCP(): RemolquesCCP
    {
        return $this->helperGetOrAdd(new RemolquesCCP());
    }

    public function addRemolquesCCP(array $attributes = []): RemolquesCCP
    {
        $subject = $this->getRemolquesCCP();
        $subject->addAttributes($attributes);
        return $subject;
    }
}
