<?php

namespace CfdiUtils\Elements\CartaPorte31;

use CfdiUtils\Elements\Common\AbstractElement;

class TransporteFerroviario extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cartaporte31:TransporteFerroviario';
    }

    public function getChildrenOrder(): array
    {
        return [
        'cartaporte31:DerechosDePaso',
        'cartaporte31:Carro'];
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
