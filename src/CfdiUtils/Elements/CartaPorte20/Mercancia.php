<?php

namespace CfdiUtils\Elements\CartaPorte20;

use CfdiUtils\Elements\Common\AbstractElement;

class Mercancia extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cartaporte20:Mercancia';
    }

    public function getChildrenOrder(): array
    {
        return [
            'cartaporte20:Pedimentos',
            'cartaporte20:GuiasIdentificacion',
            'cartaporte20:CantidadTransporta',
            'cartaporte20:DetalleMercancia',
        ];
    }

    public function addPedimentos(array $attributes = []): Pedimentos
    {
        $subject = new Pedimentos($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiPedimentos(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addPedimentos($attributes);
        }
        return $this;
    }

    public function addGuiasIdentificacion(array $attributes = []): GuiasIdentificacion
    {
        $subject = new GuiasIdentificacion($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiGuiasIdentificacion(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addGuiasIdentificacion($attributes);
        }
        return $this;
    }

    public function addCantidadTransporta(array $attributes = []): CantidadTransporta
    {
        $subject = new CantidadTransporta($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiCantidadTransporta(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addCantidadTransporta($attributes);
        }
        return $this;
    }

    public function getDetalleMercancia(): DetalleMercancia
    {
        return $this->helperGetOrAdd(new DetalleMercancia());
    }

    public function addDetalleMercancia(array $attributes = []): DetalleMercancia
    {
        $subject = $this->getDetalleMercancia();
        $subject->addAttributes($attributes);
        return $subject;
    }
}
