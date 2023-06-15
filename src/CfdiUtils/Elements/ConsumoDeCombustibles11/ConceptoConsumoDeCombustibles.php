<?php

namespace CfdiUtils\Elements\ConsumoDeCombustibles11;

use CfdiUtils\Elements\Common\AbstractElement;

class ConceptoConsumoDeCombustibles extends AbstractElement
{
    public function getElementName(): string
    {
        return 'consumodecombustibles11:ConceptoConsumoDeCombustibles';
    }

    public function getDeterminados(): Determinados
    {
        return $this->helperGetOrAdd(new Determinados());
    }

    public function addDeterminados(array $attributes = []): Determinados
    {
        $subject = $this->getDeterminados();
        $subject->addAttributes($attributes);
        return $subject;
    }
}
