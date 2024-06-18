<?php

namespace CfdiUtils\Elements\CartaPorte31;

use CfdiUtils\Elements\Common\AbstractElement;

class CartaPorte extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cartaporte31:CartaPorte';
    }

    public function getChildrenOrder(): array
    {
        return [
        'cartaporte31:RegimenesAduaneros',
        'cartaporte31:Ubicaciones',
        'cartaporte31:Mercancias',
        'cartaporte31:FiguraTransporte'];
    }

    public function getFixedAttributes(): array
    {
        return [
            'xmlns:cartaporte31' => 'http://www.sat.gob.mx/CartaPorte31',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/CartaPorte31'
                . ' http://www.sat.gob.mx/sitio_internet/cfd/CartaPorte/CartaPorte31.xsd',
            'Version' => '3.1',
        ];
    }

    public function getRegimenesAduaneros(): RegimenesAduaneros
    {
        return $this->helperGetOrAdd(new RegimenesAduaneros());
    }

    public function addRegimenesAduaneros(array $attributes = []): RegimenesAduaneros
    {
        $subject = $this->getRegimenesAduaneros();
        $subject->addAttributes($attributes);
        return $subject;
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
