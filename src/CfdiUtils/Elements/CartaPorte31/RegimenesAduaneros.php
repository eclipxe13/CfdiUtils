<?php

namespace CfdiUtils\Elements\CartaPorte31;

use CfdiUtils\Elements\Common\AbstractElement;

class RegimenesAduaneros extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cartaporte31:RegimenesAduaneros';
    }

    public function addRegimenAduaneroCCP(array $attributes = []): RegimenAduaneroCCP
    {
        $subject = new RegimenAduaneroCCP($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiRegimenAduanerCCP(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addRegimenAduaneroCCP($attributes);
        }
        return $this;
    }
}
