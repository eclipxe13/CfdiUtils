<?php

namespace CfdiUtils\Elements\CartaPorte10;

use CfdiUtils\Elements\Common\AbstractElement;

class Mercancias extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cartaporte:Mercancias';
    }

    public function addMercancia(array $attributes = []): Mercancia
    {
        $mercancia = new Mercancia($attributes);
        $this->addChild($mercancia);
        return $mercancia;
    }

    public function multiMercancia(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addMercancia($attributes);
        }
        return $this;
    }

    public function addAutotransporteFederal(array $attributes = []): AutotransporteFederal
    {
        $autotransporteFederal = new AutotransporteFederal($attributes);
        $this->addChild($autotransporteFederal);

        return $autotransporteFederal;
    }

    public function addTransporteMaritimo(array $attributes = []): TransporteMaritimo
    {
        $transporteMaritimo = new TransporteMaritimo($attributes);
        $this->addChild($transporteMaritimo);

        return $transporteMaritimo;
    }

    public function addTransporteAereo(array $attributes = []): TransporteAereo
    {
        $transporteAereo = new TransporteAereo($attributes);
        $this->addChild($transporteAereo);

        return $transporteAereo;
    }

    public function addTransporteFerroviario(array $attributes = []): TransporteFerroviario
    {
        $transporteFerroviario = new TransporteFerroviario($attributes);
        $this->addChild($transporteFerroviario);

        return $transporteFerroviario;
    }
}
