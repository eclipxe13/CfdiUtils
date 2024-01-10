<?php

namespace CfdiUtils\Elements\Cce20;

use CfdiUtils\Elements\Common\AbstractElement;

class ComercioExterior extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cce20:ComercioExterior';
    }

    public function getChildrenOrder(): array
    {
        return [
            'cce20:Emisor',
            'cce20:Propietario',
            'cce20:Receptor',
            'cce20:Destinatario',
            'cce20:Mercancias',
        ];
    }

    public function getFixedAttributes(): array
    {
        return [
            'xmlns:cce20' => 'http://www.sat.gob.mx/ComercioExterior20',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/ComercioExterior20'
                . ' http://www.sat.gob.mx/sitio_internet/cfd/ComercioExterior20/ComercioExterior20.xsd',
            'Version' => '2.0',
        ];
    }

    public function getEmisor(): Emisor
    {
        return $this->helperGetOrAdd(new Emisor());
    }

    public function addEmisor(array $attributes = []): Emisor
    {
        $subject = $this->getEmisor();
        $subject->addAttributes($attributes);
        return $subject;
    }

    public function addPropietario(array $attributes = []): Propietario
    {
        $subject = new Propietario($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiPropietario(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addPropietario($attributes);
        }
        return $this;
    }

    public function getReceptor(): Receptor
    {
        return $this->helperGetOrAdd(new Receptor());
    }

    public function addReceptor(array $attributes = []): Receptor
    {
        $subject = $this->getReceptor();
        $subject->addAttributes($attributes);
        return $subject;
    }

    public function addDestinatario(array $attributes = []): Destinatario
    {
        $subject = new Destinatario($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiDestinatario(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addDestinatario($attributes);
        }
        return $this;
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
}
