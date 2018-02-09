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

    public function multiImpuestos(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addImpuestos($attributes);
        }
        return $this;
    }

    public function getElementName(): string
    {
        return 'pagos10:Pago';
    }

    public function getChildrenOrder(): array
    {
        return ['pagos10:DoctoRelacionado', 'pagos10:Impuestos'];
    }
}
