<?php

namespace CfdiUtils\Elements\ConsumoDeCombustibles11;

use CfdiUtils\Elements\Common\AbstractElement;

class Conceptos extends AbstractElement
{
    public function getElementName(): string
    {
        return 'consumodecombustibles11:Conceptos';
    }

    public function addConceptoConsumoDeCombustibles(array $attributes = []): ConceptoConsumoDeCombustibles
    {
        $subject = new ConceptoConsumoDeCombustibles($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiConceptoConsumoDeCombustibles(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addConceptoConsumoDeCombustibles($attributes);
        }
        return $this;
    }
}
