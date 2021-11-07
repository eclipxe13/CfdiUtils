<?php

namespace CfdiUtils\Elements\CartaPorte10;

use CfdiUtils\Elements\Common\AbstractElement;

class TransporteFerroviario extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cartaporte:TransporteFerroviario';
    }

    public function addDerechosDePaso(array $attributes = []): DerechosDePaso
    {
        $derechosDePaso = new DerechosDePaso($attributes);
        $this->addChild($derechosDePaso);

        return $derechosDePaso;
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
        $carro = new Carro($attributes);
        $this->addChild($carro);

        return $carro;
    }

    public function multiCarro(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addCarro($attributes);
        }
        return $this;
    }
}
