<?php

namespace CfdiUtils\Elements\Pagos20;

use CfdiUtils\Elements\Common\AbstractElement;

class Pago extends AbstractElement
{
    public function getElementName(): string
    {
        return 'pago20:Pago';
    }

    public function getChildrenOrder(): array
    {
        return [
        'pago20:DoctoRelacionado',
        'pago20:ImpuestosP',
        ];
    }

    public function addDoctoRelacionado(array $attributes = []): DoctoRelacionado
    {
        $subject = new DoctoRelacionado($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiDoctoRelacionado(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addDoctoRelacionado($attributes);
        }
        return $this;
    }

    public function getImpuestosP(): ImpuestosP
    {
        return $this->helperGetOrAdd(new ImpuestosP());
    }

    public function addImpuestosP(array $attributes = []): ImpuestosP
    {
        $subject = $this->getImpuestosP();
        $subject->addAttributes($attributes);
        return $subject;
    }
}
