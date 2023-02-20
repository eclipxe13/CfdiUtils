<?php

namespace CfdiUtils\Elements\ConsumoDeCombustibles11;

use CfdiUtils\Elements\Common\AbstractElement;

class Determinados extends AbstractElement
{
    public function getElementName(): string
    {
        return 'consumodecombustibles11:Determinados';
    }

    public function getDeterminado(): Determinado
    {
        return $this->helperGetOrAdd(new Determinado());
    }

    public function addDeterminado(array $attributes = []): Determinado
    {
        $subject = $this->getDeterminado();
        $subject->addAttributes($attributes);
        return $subject;
    }
}
