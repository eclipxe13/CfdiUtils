<?php

namespace CfdiUtils\Elements\CartaPorte31;

use CfdiUtils\Elements\Common\AbstractElement;

class Contenedor extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cartaporte31:Contenedor';
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
