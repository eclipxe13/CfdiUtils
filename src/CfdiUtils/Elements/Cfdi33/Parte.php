<?php
namespace CfdiUtils\Elements\Cfdi33;

use CfdiUtils\Elements\Common\AbstractElement;

class Parte extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cfdi:Parte';
    }

    public function addInformacionAduanera(array $attributes = []): InformacionAduanera
    {
        $informacionAduanera = new InformacionAduanera($attributes);
        $this->addChild($informacionAduanera);
        return $informacionAduanera;
    }
}
