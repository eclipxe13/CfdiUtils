<?php

namespace CfdiUtils\Elements\ParcialesConstruccion10;

use CfdiUtils\Elements\Common\AbstractElement;

class ParcialesConstruccion extends AbstractElement
{
    public function getElementName(): string
    {
        return 'servicioparcial:ParcialesConstruccion';
    }

    public function getFixedAttributes(): array
    {
        return [
            'xmlns:servicioparcial' => 'http://www.sat.gob.mx/implocal',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/implocal'
                . ' http://www.sat.gob.mx/sitio_internet/cfd/implocal/implocal.xsd',
            'Version' => '1.0',
        ];
    }

    public function getInmueble(): Inmueble
    {
        return $this->helperGetOrAdd(new Inmueble());
    }

    public function addInmueble(array $attributes = []): Inmueble
    {
        $subject = $this->getInmueble();
        $subject->addAttributes($attributes);
        return $subject;
    }
}
