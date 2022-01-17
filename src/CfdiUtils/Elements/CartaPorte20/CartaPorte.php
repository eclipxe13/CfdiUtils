<?php

namespace CfdiUtils\Elements\CartaPorte20;

use CfdiUtils\Elements\Common\AbstractElement;

class CartaPorte extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cartaporte20:CartaPorte';
    }

    public function getChildrenOrder(): array
    {
        return [
            'cartaporte20:Ubicaciones',
            'cartaporte20:Mercancias',
            'cartaporte20:FiguraTransporte',
        ];
    }

    public function getFixedAttributes(): array
    {
        return [
            'xmlns:cartaporte20' => 'http://www.sat.gob.mx/CartaPorte20',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/CartaPorte20'
                . ' http://www.sat.gob.mx/sitio_internet/cfd/CartaPorte/CartaPorte20.xsd',
            'Version' => '2.0',
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
