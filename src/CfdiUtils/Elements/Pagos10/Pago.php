<?php

namespace CfdiUtils\Elements\Pagos10;

use CfdiUtils\Elements\Common\AbstractElement;

class Pago extends AbstractElement
{
    public function addDoctoRelacionado(array $attributes = []): DoctoRelacionado
    {
        $doctoRelacionado = new DoctoRelacionado($attributes);
        $this->addChild($doctoRelacionado);
        return $doctoRelacionado;
    }

    public function multiDoctoRelacionado(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addDoctoRelacionado($attributes);
        }
        return $this;
    }

    public function addImpuestos(array $attributes = []): Impuestos
    {
        $impuestos = new Impuestos($attributes);
        $this->addChild($impuestos);
        return $impuestos;
    }

    public function getElementName(): string
    {
        return 'pago10:Pago';
    }

    public function getChildrenOrder(): array
    {
        return ['pago10:DoctoRelacionado', 'pago10:Impuestos'];
    }
}
