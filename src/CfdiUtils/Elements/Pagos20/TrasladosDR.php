<?php

namespace CfdiUtils\Elements\Pagos20;

use CfdiUtils\Elements\Common\AbstractElement;

class TrasladosDR extends AbstractElement
{
    public function getElementName(): string
    {
        return 'pago20:TrasladosDR';
    }

    public function addTrasladoDR(array $attributes = []): TrasladoDR
    {
        $subject = new TrasladoDR($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiTrasladoDR(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addTrasladoDR($attributes);
        }
        return $this;
    }
}
