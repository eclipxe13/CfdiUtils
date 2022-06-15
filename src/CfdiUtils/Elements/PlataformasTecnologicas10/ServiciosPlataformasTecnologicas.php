<?php

namespace CfdiUtils\Elements\PlataformasTecnologicas10;

use CfdiUtils\Elements\Common\AbstractElement;

class ServiciosPlataformasTecnologicas extends AbstractElement
{
    public function getElementName(): string
    {
        return 'plataformasTecnologicas:ServiciosPlataformasTecnologicas';
    }

    public function getFixedAttributes(): array
    {
        return [
            'xmlns:plataformasTecnologicas' => 'http://www.sat.gob.mx/esquemas/retencionpago/1'
                . '/PlataformasTecnologicas10',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/esquemas/retencionpago/1/PlataformasTecnologicas10'
                . ' http://www.sat.gob.mx/esquemas/retencionpago/1/PlataformasTecnologicas10'
                . '/ServiciosPlataformasTecnologicas10.xsd',
            'Version' => '1.0',
        ];
    }

    public function getServicios(): Servicios
    {
        return $this->helperGetOrAdd(new Servicios());
    }

    public function addServicios(array $attributes = []): Servicios
    {
        $subject = $this->getServicios();
        $subject->addAttributes($attributes);
        return $subject;
    }
}
