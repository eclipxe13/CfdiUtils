<?php

namespace CfdiUtils\Elements\Cfdi33;

use CfdiUtils\Elements\Common\AbstractElement;

class Conceptos extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cfdi:Conceptos';
    }

    public function addConcepto(array $attributes = [], array $children = []): Concepto
    {
        $concepto = new Concepto($attributes, $children);
        $this->addChild($concepto);
        return $concepto;
    }
}
