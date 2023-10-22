<?php

namespace CfdiUtils\Elements\CartaPorte30;

use CfdiUtils\Elements\Common\AbstractElement;

class CartaPorte extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cartaporte30:CartaPorte';
    }

    public function getChildrenOrder(): array
    {
        return [
            'cartaporte30:Ubicaciones',
            'cartaporte30:Mercancias',
            'cartaporte30:FiguraTransporte',
        ];
    }

    public function getFixedAttributes(): array
    {
        return [
            'xmlns:cartaporte30' => 'http://www.sat.gob.mx/CartaPorte30',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/CartaPorte30'
                . ' http://www.sat.gob.mx/sitio_internet/cfd/CartaPorte/CartaPorte30.xsd',
            'Version' => '3.0',
        ];
    }

    public function getUbicaciones(): Ubicaciones
    {
        return $this->helperGetOrAdd(new Ubicaciones());
    }

    public function addUbicaciones(array $attributes = []): Ubicaciones
    {
        $subject = $this->getUbicaciones();
        $subject->addAttributes($attributes);
        return $subject;
    }

    public function getMercancias(): Mercancias
    {
        return $this->helperGetOrAdd(new Mercancias());
    }

    public function addMercancias(array $attributes = []): Mercancias
    {
        $subject = $this->getMercancias();
        $subject->addAttributes($attributes);
        return $subject;
    }

    public function getFiguraTransporte(): FiguraTransporte
    {
        return $this->helperGetOrAdd(new FiguraTransporte());
    }

    public function addFiguraTransporte(array $attributes = []): FiguraTransporte
    {
        $subject = $this->getFiguraTransporte();
        $subject->addAttributes($attributes);
        return $subject;
    }
}
