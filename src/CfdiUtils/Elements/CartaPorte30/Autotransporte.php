<?php

namespace CfdiUtils\Elements\CartaPorte30;

use CfdiUtils\Elements\Common\AbstractElement;

class Autotransporte extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cartaporte30:Autotransporte';
    }

    public function getChildrenOrder(): array
    {
        return [
            'cartaporte30:IdentificacionVehicular',
            'cartaporte30:Seguros',
            'cartaporte30:Remolques',
        ];
    }

    public function getIdentificacionVehicular(): IdentificacionVehicular
    {
        return $this->helperGetOrAdd(new IdentificacionVehicular());
    }

    public function addIdentificacionVehicular(array $attributes = []): IdentificacionVehicular
    {
        $subject = $this->getIdentificacionVehicular();
        $subject->addAttributes($attributes);
        return $subject;
    }

    public function getSeguros(): Seguros
    {
        return $this->helperGetOrAdd(new Seguros());
    }

    public function addSeguros(array $attributes = []): Seguros
    {
        $subject = $this->getSeguros();
        $subject->addAttributes($attributes);
        return $subject;
    }

    public function getRemolques(): Remolques
    {
        return $this->helperGetOrAdd(new Remolques());
    }

    public function addRemolques(array $attributes = []): Remolques
    {
        $subject = $this->getRemolques();
        $subject->addAttributes($attributes);
        return $subject;
    }
}
