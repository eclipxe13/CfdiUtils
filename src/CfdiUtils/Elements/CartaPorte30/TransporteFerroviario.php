<?php

namespace CfdiUtils\Elements\CartaPorte30;

use CfdiUtils\Elements\Common\AbstractElement;

class TransporteFerroviario extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cartaporte30:TransporteFerroviario';
    }

    public function getChildrenOrder(): array
    {
        return [
            'cartaporte30:DerechosDePaso',
            'cartaporte30:Carro',
        ];
    }

    public function addDerechosDePaso(array $attributes = []): DerechosDePaso
    {
        $subject = new DerechosDePaso($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiDerechosDePaso(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addDerechosDePaso($attributes);
        }
        return $this;
    }

    public function addCarro(array $attributes = []): Carro
    {
        $subject = new Carro($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiCarro(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addCarro($attributes);
        }
        return $this;
    }
}
