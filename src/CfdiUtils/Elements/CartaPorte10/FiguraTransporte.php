<?php

namespace CfdiUtils\Elements\CartaPorte10;

use CfdiUtils\Elements\Common\AbstractElement;

class FiguraTransporte extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cartaporte:FiguraTransporte';
    }

    public function addOperadores(array $attributes = []): Operadores
    {
        $operadores = new Operadores($attributes);
        $this->addChild($operadores);

        return $operadores;
    }

    public function multiOperadores(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addOperadores($attributes);
        }
        return $this;
    }

    public function addPropietario(array $attributes = []): Propietario
    {
        $propietario = new Propietario($attributes);
        $this->addChild($propietario);

        return $propietario;
    }

    public function multiPropietario(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addPropietario($attributes);
        }
        return $this;
    }

    public function addArrendatario(array $attributes = []): Arrendatario
    {
        $arrendatario = new Arrendatario($attributes);
        $this->addChild($arrendatario);

        return $arrendatario;
    }

    public function multiArrendatario(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addArrendatario($attributes);
        }
        return $this;
    }

    public function addNotificado(array $attributes = []): Notificado
    {
        $arrendatario = new Notificado($attributes);
        $this->addChild($arrendatario);

        return $arrendatario;
    }

    public function multiNotificado(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addNotificado($attributes);
        }
        return $this;
    }
}
