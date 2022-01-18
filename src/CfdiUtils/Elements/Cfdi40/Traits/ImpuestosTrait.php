<?php

namespace CfdiUtils\Elements\Cfdi40\Traits;

use CfdiUtils\Elements\Cfdi40\Impuestos;
use CfdiUtils\Elements\Cfdi40\Retencion;
use CfdiUtils\Elements\Cfdi40\Traslado;

trait ImpuestosTrait
{
    /*
     * This method is required for all the shortcut methods included here
     * The returned instance must be Impuestos or an extended class
     */
    abstract protected function getElementImpuestos(): Impuestos;

    public function addTraslado(array $attributes = []): Traslado
    {
        return $this->getElementImpuestos()->getTraslados()->addTraslado($attributes);
    }

    public function multiTraslado(array ...$elementAttributes): self
    {
        $this->getElementImpuestos()->getTraslados()->multiTraslado(...$elementAttributes);
        return $this;
    }

    public function addRetencion(array $attributes = []): Retencion
    {
        return $this->getElementImpuestos()->getRetenciones()->addRetencion($attributes);
    }

    public function multiRetencion(array ...$elementAttributes): self
    {
        $this->getElementImpuestos()->getRetenciones()->multiRetencion(...$elementAttributes);
        return $this;
    }
}
