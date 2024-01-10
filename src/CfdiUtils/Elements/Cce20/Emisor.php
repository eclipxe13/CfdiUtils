<?php

namespace CfdiUtils\Elements\Cce20;

use CfdiUtils\Elements\Common\AbstractElement;

class Emisor extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cce20:Emisor';
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
