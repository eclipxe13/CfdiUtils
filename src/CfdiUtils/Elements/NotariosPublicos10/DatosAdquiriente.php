<?php

namespace CfdiUtils\Elements\NotariosPublicos10;

use CfdiUtils\Elements\Common\AbstractElement;

class DatosAdquiriente extends AbstractElement
{
    public function getElementName(): string
    {
        return 'notariospublicos:DatosAdquiriente';
    }

    public function getDatosUnAdquiriente(): DatosUnAdquiriente
    {
        return $this->helperGetOrAdd(new DatosUnAdquiriente());
    }

    public function addDatosUnAdquiriente(array $attributes = []): DatosUnAdquiriente
    {
        $subject = $this->getDatosUnAdquiriente();
        $subject->addAttributes($attributes);
        return $subject;
    }
}
