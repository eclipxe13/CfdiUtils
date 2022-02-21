<?php

namespace CfdiUtils\Elements\Cfdi40;

use CfdiUtils\Elements\Common\AbstractElement;

class Parte extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cfdi:Parte';
    }

    public function addInformacionAduanera(array $attributes = []): InformacionAduanera
    {
        $subject = new InformacionAduanera($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiInformacionAduanera(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addInformacionAduanera($attributes);
        }
        return $this;
    }
}
