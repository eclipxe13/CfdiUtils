<?php

namespace CfdiUtils\Elements\CartaPorte31;

use CfdiUtils\Elements\Common\AbstractElement;

class RegimenesAduaneros extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cartaporte31:RegimenesAduaneros';
    }

    public function addRegimenAduanerCCP(array $attributes = []): RegimenAduanerCCP
    {
        $subject = new RegimenAduanerCCP($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiRegimenAduanerCCP(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addRegimenAduanerCCP($attributes);
        }
        return $this;
    }
}
