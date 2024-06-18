<?php

namespace CfdiUtils\Elements\CartaPorte31;

use CfdiUtils\Elements\Common\AbstractElement;

class Mercancias extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cartaporte31:Mercancias';
    }

    public function getChildrenOrder(): array
    {
        return [
        'cartaporte31:Mercancia',
        'cartaporte31:Autotransporte',
        'cartaporte31:TransporteMaritimo',
        'cartaporte31:TransporteAereo',
        'cartaporte31:TransporteFerroviario'];
    }

    public function addMercancia(array $attributes = []): Mercancia
    {
        $subject = new Mercancia($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiMercancia(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addMercancia($attributes);
        }
        return $this;
    }

    public function getAutotransporte(): Autotransporte
    {
        return $this->helperGetOrAdd(new Autotransporte());
    }

    public function addAutotransporte(array $attributes = []): Autotransporte
    {
        $subject = $this->getAutotransporte();
        $subject->addAttributes($attributes);
        return $subject;
    }

    public function getTransporteMaritimo(): TransporteMaritimo
    {
        return $this->helperGetOrAdd(new TransporteMaritimo());
    }

    public function addTransporteMaritimo(array $attributes = []): TransporteMaritimo
    {
        $subject = $this->getTransporteMaritimo();
        $subject->addAttributes($attributes);
        return $subject;
    }

    public function getTransporteAereo(): TransporteAereo
    {
        return $this->helperGetOrAdd(new TransporteAereo());
    }

    public function addTransporteAereo(array $attributes = []): TransporteAereo
    {
        $subject = $this->getTransporteAereo();
        $subject->addAttributes($attributes);
        return $subject;
    }

    public function getTransporteFerroviario(): TransporteFerroviario
    {
        return $this->helperGetOrAdd(new TransporteFerroviario());
    }

    public function addTransporteFerroviario(array $attributes = []): TransporteFerroviario
    {
        $subject = $this->getTransporteFerroviario();
        $subject->addAttributes($attributes);
        return $subject;
    }
}
