<?php
namespace CfdiUtils\Elements\Cfdi33;

use CfdiUtils\Elements\Cfdi33\Traits\InformacionAduaneraTrait;
use CfdiUtils\Elements\Common\AbstractElement;

class Concepto extends AbstractElement
{
    use InformacionAduaneraTrait;

    public function getElementName(): string
    {
        return 'cfdi:Concepto';
    }

    public function getImpuestos(): Impuestos
    {
        return $this->helperGetOrAdd(new Impuestos());
    }

    public function addTraslado(array $attributes = []): Traslado
    {
        return $this->getImpuestos()->getTraslados()->addTraslado($attributes);
    }

    public function multiTraslado(array ...$elementAttributes)
    {
        return $this->getImpuestos()->getTraslados()->multiTraslado(...$elementAttributes);
    }

    public function addRetencion(array $attributes = []): Retencion
    {
        return $this->getImpuestos()->getRetenciones()->addRetencion($attributes);
    }

    public function multiRetencion(array ...$elementAttributes)
    {
        return $this->getImpuestos()->getRetenciones()->multiRetencion(...$elementAttributes);
    }

    public function getCuentaPredial(): CuentaPredial
    {
        return $this->helperGetOrAdd(new CuentaPredial());
    }

    public function addCuentaPredial(array $attributes = []): CuentaPredial
    {
        $cuentaPredial = $this->getCuentaPredial();
        $cuentaPredial->addAttributes($attributes);
        return $cuentaPredial;
    }

    public function getComplementoConcepto(): ComplementoConcepto
    {
        return $this->helperGetOrAdd(new ComplementoConcepto());
    }

    public function addComplementoConcepto(array $attributes = [], array $children = []): ComplementoConcepto
    {
        $complementoConcepto = $this->getComplementoConcepto();
        $complementoConcepto->addAttributes($attributes);
        $complementoConcepto->children()->importFromArray($children);
        return $complementoConcepto;
    }

    public function addParte(array $attributes = [], array $children = []): Parte
    {
        $parte = new Parte($attributes, $children);
        $this->addChild($parte);
        return $parte;
    }

    public function multiParte(array ...$elementAttributes)
    {
        foreach ($elementAttributes as $attributes) {
            $this->addParte($attributes);
        }
    }
}
