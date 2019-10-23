<?php

namespace CfdiUtils\Elements\Cce11;

use CfdiUtils\Elements\Common\AbstractElement;

class Destinatario extends AbstractElement
{
    public function addDomicilio(array $attributes = []): Domicilio
    {
        $domicilio = new Domicilio($attributes);
        $this->addChild($domicilio);
        return $domicilio;
    }

    public function getElementName(): string
    {
        return 'cce11:Destinatario';
    }
}
