<?php

namespace CfdiUtils\Elements\Retenciones10;

use CfdiUtils\Elements\Common\AbstractElement;

class Receptor extends AbstractElement
{
    public function getElementName(): string
    {
        return 'retenciones:Receptor';
    }

    public function getNacional(): Nacional
    {
        $nacional = $this->helperGetOrAdd(new Nacional());
        $this->children()->removeAll();
        $this->addChild($nacional);
        $this['Nacionalidad'] = 'Nacional';
        return $nacional;
    }

    public function addNacional(array $attributes = []): Nacional
    {
        $nacional = $this->getNacional();
        $nacional->addAttributes($attributes);
        return $nacional;
    }

    public function getExtranjero(): Extranjero
    {
        $nacional = $this->helperGetOrAdd(new Extranjero());
        $this->children()->removeAll();
        $this->addChild($nacional);
        $this['Nacionalidad'] = 'Extranjero';
        return $nacional;
    }

    public function addExtranjero(array $attributes = []): Extranjero
    {
        $extranjero = $this->getExtranjero();
        $extranjero->addAttributes($attributes);
        return $extranjero;
    }
}
