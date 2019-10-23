<?php

namespace CfdiUtils\Elements\ImpLocal10;

use CfdiUtils\Elements\Common\AbstractElement;

class ImpuestosLocales extends AbstractElement
{
    public function addRetencionLocal(array $attributes = []): RetencionesLocales
    {
        $retencion = new RetencionesLocales($attributes);
        $this->addChild($retencion);
        return $retencion;
    }

    public function addTrasladoLocal(array $attributes = []): TrasladosLocales
    {
        $traslado = new TrasladosLocales($attributes);
        $this->addChild($traslado);
        return $traslado;
    }

    public function getElementName(): string
    {
        return 'implocal:ImpuestosLocales';
    }

    public function getChildrenOrder(): array
    {
        return ['implocal:RetencionesLocales', 'implocal:TrasladosLocales'];
    }

    public function getFixedAttributes(): array
    {
        return [
            'xmlns:implocal' => 'http://www.sat.gob.mx/implocal',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/implocal'
                . ' http://www.sat.gob.mx/sitio_internet/cfd/implocal/implocal.xsd',
            'version' => '1.0',
        ];
    }
}
