<?php

namespace CfdiUtils\Elements\Cce11;

use CfdiUtils\Elements\Common\AbstractElement;

class ComercioExterior extends AbstractElement
{
    public function getEmisor(): Emisor
    {
        return $this->helperGetOrAdd(new Emisor());
    }

    public function addEmisor(array $attributes = []): Emisor
    {
        $emisor = $this->getEmisor();
        $emisor->addAttributes($attributes);
        return $emisor;
    }

    public function getReceptor(): Receptor
    {
        return $this->helperGetOrAdd(new Receptor());
    }

    public function addReceptor(array $attributes = []): Receptor
    {
        $receptor = $this->getReceptor();
        $receptor->addAttributes($attributes);
        return $receptor;
    }

    public function addPropietario(array $attributes = []): Propietario
    {
        $propietario = new Propietario($attributes);
        $this->addChild($propietario);
        return $propietario;
    }

    public function addDestinatario(array $attributes = []): Destinatario
    {
        $destinatario = new Destinatario($attributes);
        $this->addChild($destinatario);
        return $destinatario;
    }

    public function getMercancias(): Mercancias
    {
        return $this->helperGetOrAdd(new Mercancias());
    }

    public function addMercancias(array $attributes = []): Mercancias
    {
        $mercancias = $this->getMercancias();
        $mercancias->addAttributes($attributes);
        return $mercancias;
    }

    public function addMercancia(array $attributes = []): Mercancia
    {
        return $this->getMercancias()->addMercancia($attributes);
    }

    public function getElementName(): string
    {
        return 'cce11:ComercioExterior';
    }

    public function getChildrenOrder(): array
    {
        return [
            'cce11:Emisor',
            'cce11:Propietario',
            'cce11:Receptor',
            'cce11:Destinatario',
            'cce11:Mercancias',
        ];
    }

    public function getFixedAttributes(): array
    {
        return [
            'xmlns:cce11' => 'http://www.sat.gob.mx/ComercioExterior11',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/ComercioExterior11'
                . ' http://www.sat.gob.mx/sitio_internet/cfd/ComercioExterior11/ComercioExterior11.xsd',
            'Version' => '1.1',
        ];
    }
}
