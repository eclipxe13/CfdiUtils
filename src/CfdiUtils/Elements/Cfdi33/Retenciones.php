<?php
namespace CfdiUtils\Elements\Cfdi33;

use CfdiUtils\Elements\Common\AbstractElement;

class Retenciones extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cfdi:Retenciones';
    }

    public function addRetencion(array $attributes = []): Retencion
    {
        $Retencion = new Retencion($attributes);
        $this->addChild($Retencion);
        return $Retencion;
    }

    public function multiRetencion(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addRetencion($attributes);
        }
        return $this;
    }
}
