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
    abstract protected function getImpuestos(): Impuestos;

    public function addTraslado(array $attributes): Traslado
    {
        return $this->getImpuestos()->getTraslados()->addTraslado($attributes);
    }

    public function multiTraslado(array ...$elementAttributes): self
    {
        $this->getImpuestos()->getTraslados()->multiTraslado(...$elementAttributes);
        return $this;
    }

    public function addRetencion(array $attributes): Retencion
    {
        return $this->getImpuestos()->getRetenciones()->addRetencion($attributes);
    }

    public function multiRetencion(array ...$elementAttributes): self
    {
        $this->getImpuestos()->getRetenciones()->multiRetencion(...$elementAttributes);
        return $this;
    }
}
