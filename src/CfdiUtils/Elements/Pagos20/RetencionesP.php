<?php

namespace CfdiUtils\Elements\Pagos20;

use CfdiUtils\Elements\Common\AbstractElement;

class RetencionesP extends AbstractElement
{
    public function getElementName(): string
    {
        return 'pago20:RetencionesP';
    }

    public function addRetencionP(array $attributes = []): RetencionP
    {
        $subject = new RetencionP($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiRetencionP(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addRetencionP($attributes);
        }
        return $this;
    }
}
