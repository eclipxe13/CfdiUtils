<?php

namespace CfdiUtils\Elements\NotariosPublicos10;

use CfdiUtils\Elements\Common\AbstractElement;

class DatosEnajenante extends AbstractElement
{
    public function getElementName(): string
    {
        return 'notariospublicos:DatosEnajenante';
    }

    public function getChildrenOrder(): array
    {
        return [
            'notariospublicos:DatosUnEnajenante',
            'notariospublicos:DatosEnajenantesCopSC',
        ];
    }

    public function getDatosUnEnajenante(): DatosUnEnajenante
    {
        return $this->helperGetOrAdd(new DatosUnEnajenante());
    }

    public function addDatosUnEnajenante(array $attributes = []): DatosUnEnajenante
    {
        $subject = $this->getDatosUnEnajenante();
        $subject->addAttributes($attributes);
        return $subject;
    }

    public function getDatosEnajenantesCopSC(): DatosEnajenantesCopSC
    {
        return $this->helperGetOrAdd(new DatosEnajenantesCopSC());
    }

    public function addDatosEnajenantesCopSC(array $attributes = []): DatosEnajenantesCopSC
    {
        $subject = $this->getDatosEnajenantesCopSC();
        $subject->addAttributes($attributes);
        return $subject;
    }
}
