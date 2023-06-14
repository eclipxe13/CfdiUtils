<?php

namespace CfdiUtils\Elements\ParcialesContruccion10;

use CfdiUtils\Elements\Common\AbstractElement;

class ParcialesConstruccion extends AbstractElement
{
    public function getElementName(): string
    {
        return 'servicioparcial:parcialesconstruccion';
    }

    public function getFixedAttributes(): array
    {
        return [
            'xmlns:servicioparcial' => 'http://www.sat.gob.mx/servicioparcialconstruccion',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/servicioparcialconstruccion'
                . ' http://www.sat.gob.mx/sitio_internet/cfd'
                . '/servicioparcialconstruccion/servicioparcialconstruccion.xsd',
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
