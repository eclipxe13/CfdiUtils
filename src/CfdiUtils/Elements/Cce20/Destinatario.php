<?php

namespace CfdiUtils\Elements\Cce20;

use CfdiUtils\Elements\Common\AbstractElement;

class Destinatario extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cce20:Destinatario';
    }

    public function addDomicilio(array $attributes = []): Domicilio
    {
        $subject = new Domicilio($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiDomicilio(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addDomicilio($attributes);
        }
        return $this;
    }
}
