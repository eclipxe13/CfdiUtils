<?php

namespace CfdiUtils\Elements\Pagos20;

use CfdiUtils\Elements\Common\AbstractElement;

class RetencionesDR extends AbstractElement
{
    public function getElementName(): string
    {
        return 'pago20:RetencionesDR';
    }

    public function addRetencionDR(array $attributes = []): RetencionDR
    {
        $subject = new RetencionDR($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiRetencionDR(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addRetencionDR($attributes);
        }
        return $this;
    }
}
