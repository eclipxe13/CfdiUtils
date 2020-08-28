<?php

namespace CfdiUtils\Elements\Nomina12;

use CfdiUtils\Elements\Common\AbstractElement;

class Percepciones extends AbstractElement
{
    public function getElementName(): string
    {
        return 'nomina12:Percepciones';
    }

    public function getChildrenOrder(): array
    {
        return [
            'nomina12:Percepcion',
            'nomina12:JubilacionPensionRetiro',
            'nomina12:SeparacionIndemnizacion',
        ];
    }

    public function addPercepcion(array $attributes, array $children = []): Percepcion
    {
        $percepcion = new Percepcion($attributes, $children);
        $this->addChild($percepcion);
        return $percepcion;
    }

    public function multiPercepcion(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addPercepcion($attributes);
        }
        return $this;
    }

    public function getJubilacionPensionRetiro(): JubilacionPensionRetiro
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->helperGetOrAdd(new JubilacionPensionRetiro());
    }

    public function addJubilacionPensionRetiro(array $attributes = []): JubilacionPensionRetiro
    {
        $jubilacionPensionRetiro = $this->getJubilacionPensionRetiro();
        $jubilacionPensionRetiro->addAttributes($attributes);
        return $jubilacionPensionRetiro;
    }

    public function getSeparacionIndemnizacion(): SeparacionIndemnizacion
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->helperGetOrAdd(new SeparacionIndemnizacion());
    }

    public function addSeparacionIndemnizacion(array $attributes = []): SeparacionIndemnizacion
    {
        $separacionIndemnizacion = $this->getSeparacionIndemnizacion();
        $separacionIndemnizacion->addAttributes($attributes);
        return $separacionIndemnizacion;
    }
}
