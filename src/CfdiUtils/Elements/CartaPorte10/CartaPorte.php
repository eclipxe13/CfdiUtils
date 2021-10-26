<?php

namespace CfdiUtils\Elements\CartaPorte10;

use CfdiUtils\Elements\Common\AbstractElement;

class CartaPorte extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cartaporte:CartaPorte';
    }

    public function getChildrenOrder(): array
    {
        return [
            'cartaporte:Ubicaciones',
            'cartaporte:Mercancias',
            'cartaporte:FiguraTransporte',
        ];
    }

    public function getFixedAttributes(): array
    {
        return [
            'xmlns:cartaporte' => 'http://www.sat.gob.mx/cartaporte',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/cartaporte'
                . ' http://www.sat.gob.mx/sitio_internet/cfd/CartaPorte/CartaPorte.xsd',
            'Version' => '1.0',
        ];
    }

    public function getUbicaciones(): Ubicaciones
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->helperGetOrAdd(new Ubicaciones());
    }

    public function addUbicaciones(array $attributes = []): Ubicaciones
    {
        $ubicaciones = $this->getUbicaciones();
        $ubicaciones->addAttributes($attributes);

        return $ubicaciones;
    }

    public function getMercancias(): Mercancias
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->helperGetOrAdd(new Mercancias());
    }

    public function addMercancias(array $attributes = []): Mercancias
    {
        $mercancias = $this->getMercancias();
        $mercancias->addAttributes($attributes);

        return $mercancias;
    }

    public function getFiguraTransporte(): FiguraTransporte
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->helperGetOrAdd(new FiguraTransporte());
    }

    public function addFiguraTransporte(array $attributes = []): FiguraTransporte
    {
        $figuraTransporte = $this->getFiguraTransporte();
        $figuraTransporte->addAttributes($attributes);

        return $figuraTransporte;
    }
}
