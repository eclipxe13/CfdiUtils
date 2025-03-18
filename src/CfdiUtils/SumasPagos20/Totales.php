<?php

namespace CfdiUtils\SumasPagos20;

use JsonSerializable;

final class Totales implements JsonSerializable
{
    public function __construct(
        private ?Decimal $retencionIva,
        private ?Decimal $retencionIsr,
        private ?Decimal $retencionIeps,
        private ?Decimal $trasladoIva16Base,
        private ?Decimal $trasladoIva16Importe,
        private ?Decimal $trasladoIva08Base,
        private ?Decimal $trasladoIva08Importe,
        private ?Decimal $trasladoIva00Base,
        private ?Decimal $trasladoIva00Importe,
        private ?Decimal $trasladoIvaExento,
        private Decimal $total,
    ) {
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
