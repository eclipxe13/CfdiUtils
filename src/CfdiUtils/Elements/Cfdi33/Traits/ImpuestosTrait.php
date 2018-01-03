<?php
namespace CfdiUtils\Elements\Cfdi33\Traits;

use CfdiUtils\Elements\Cfdi33\Impuestos;
use CfdiUtils\Elements\Cfdi33\Retencion;
use CfdiUtils\Elements\Cfdi33\Traslado;
use CfdiUtils\Elements\Common\ElementInterface;

trait ImpuestosTrait
{
    /* This method comes from AbstractElement */
    abstract protected function helperGetOrAdd(ElementInterface $element);

    public function getImpuestos(): Impuestos
    {
        return $this->helperGetOrAdd(new Impuestos());
    }

    public function addTraslado(array $attributes = []): Traslado
    {
        return $this->getImpuestos()->getTraslados()->addTraslado($attributes);
    }

    public function multiTraslado(array ...$elementAttributes): self
    {
        $this->getImpuestos()->getTraslados()->multiTraslado(...$elementAttributes);
        return $this;
    }

    public function addRetencion(array $attributes = []): Retencion
    {
        return $this->getImpuestos()->getRetenciones()->addRetencion($attributes);
    }

    public function multiRetencion(array ...$elementAttributes): self
    {
        $this->getImpuestos()->getRetenciones()->multiRetencion(...$elementAttributes);
        return $this;
    }
}
