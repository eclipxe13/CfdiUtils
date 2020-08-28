<?php

namespace CfdiUtils\Elements\Nomina12;

use CfdiUtils\Elements\Common\AbstractElement;

class Percepcion extends AbstractElement
{
    public function getElementName(): string
    {
        return 'nomina12:Percepcion';
    }

    public function getChildrenOrder(): array
    {
        return ['nomina12:AccionesOTitulos', 'nomina12:HorasExtra'];
    }

    public function getAccionesOTitulos(): AccionesOTitulos
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->helperGetOrAdd(new AccionesOTitulos());
    }

    public function addAccionesOTitulos(array $attributes = []): AccionesOTitulos
    {
        $accionesOTitulos = $this->getAccionesOTitulos();
        $accionesOTitulos->addAttributes($attributes);
        return $accionesOTitulos;
    }

    public function addHorasExtra(array $attributes, array $children = []): HorasExtra
    {
        $horasExtra = new HorasExtra($attributes, $children);
        $this->addChild($horasExtra);
        return $horasExtra;
    }

    public function multiHorasExtra(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addHorasExtra($attributes);
        }
        return $this;
    }
}
