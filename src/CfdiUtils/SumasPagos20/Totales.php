<?php

namespace CfdiUtils\SumasPagos20;

use JsonSerializable;

final class Totales implements JsonSerializable
{
    /** @var Decimal|null */
    private $retencionIva;

    /** @var Decimal|null */
    private $retencionIsr;

    /** @var Decimal|null */
    private $retencionIeps;

    /** @var Decimal|null */
    private $trasladoIva16Base;

    /** @var Decimal|null */
    private $trasladoIva16Importe;

    /** @var Decimal|null */
    private $trasladoIva08Base;

    /** @var Decimal|null */
    private $trasladoIva08Importe;

    /** @var Decimal|null */
    private $trasladoIva00Base;

    /** @var Decimal|null */
    private $trasladoIva00Importe;

    /** @var Decimal|null */
    private $trasladoIvaExento;

    /** @var Decimal */
    private $total;

    public function __construct(
        ?Decimal $retencionIva,
        ?Decimal $retencionIsr,
        ?Decimal $retencionIeps,
        ?Decimal $trasladoIva16Base,
        ?Decimal $trasladoIva16Importe,
        ?Decimal $trasladoIva08Base,
        ?Decimal $trasladoIva08Importe,
        ?Decimal $trasladoIva00Base,
        ?Decimal $trasladoIva00Importe,
        ?Decimal $trasladoIvaExento,
        Decimal $total
    ) {
        $this->retencionIva = $retencionIva;
        $this->retencionIsr = $retencionIsr;
        $this->retencionIeps = $retencionIeps;
        $this->trasladoIva16Base = $trasladoIva16Base;
        $this->trasladoIva16Importe = $trasladoIva16Importe;
        $this->trasladoIva08Base = $trasladoIva08Base;
        $this->trasladoIva08Importe = $trasladoIva08Importe;
        $this->trasladoIva00Base = $trasladoIva00Base;
        $this->trasladoIva00Importe = $trasladoIva00Importe;
        $this->trasladoIvaExento = $trasladoIvaExento;
        $this->total = $total;
    }

    public function getRetencionIva(): ?Decimal
    {
        return $this->retencionIva;
    }

    public function getRetencionIsr(): ?Decimal
    {
        return $this->retencionIsr;
    }

    public function getRetencionIeps(): ?Decimal
    {
        return $this->retencionIeps;
    }

    public function getTrasladoIva16Base(): ?Decimal
    {
        return $this->trasladoIva16Base;
    }

    public function getTrasladoIva16Importe(): ?Decimal
    {
        return $this->trasladoIva16Importe;
    }

    public function getTrasladoIva08Base(): ?Decimal
    {
        return $this->trasladoIva08Base;
    }

    public function getTrasladoIva08Importe(): ?Decimal
    {
        return $this->trasladoIva08Importe;
    }

    public function getTrasladoIva00Base(): ?Decimal
    {
        return $this->trasladoIva00Base;
    }

    public function getTrasladoIva00Importe(): ?Decimal
    {
        return $this->trasladoIva00Importe;
    }

    public function getTrasladoIvaExento(): ?Decimal
    {
        return $this->trasladoIvaExento;
    }

    public function getTotal(): Decimal
    {
        return $this->total;
    }

    /** @return array<string, Decimal> */
    public function jsonSerialize(): array
    {
        return array_filter([
            'retencionIva' => $this->retencionIva,
            'retencionIsr' => $this->retencionIsr,
            'retencionIeps' => $this->retencionIeps,
            'trasladoIva16Base' => $this->trasladoIva16Base,
            'trasladoIva16Importe' => $this->trasladoIva16Importe,
            'trasladoIva08Base' => $this->trasladoIva08Base,
            'trasladoIva08Importe' => $this->trasladoIva08Importe,
            'trasladoIva00Base' => $this->trasladoIva00Base,
            'trasladoIva00Importe' => $this->trasladoIva00Importe,
            'trasladoIvaExento' => $this->trasladoIvaExento,
            'total' => $this->total,
        ]);
    }
}
