<?php

namespace CfdiUtils\SumasPagos20;

use JsonSerializable;

final class Impuesto implements JsonSerializable
{
    private string $tipo;

    private string $impuesto;

    private string $tipoFactor;

    private string $tasaCuota;

    private Decimal $base;

    private Decimal $importe;

    public function __construct(
        string $tipo,
        string $impuesto,
        string $tipoFactor,
        string $tasaCuota,
        Decimal $base,
        Decimal $importe
    ) {
        $this->tipo = $tipo;
        $this->impuesto = $impuesto;
        $this->tipoFactor = $tipoFactor;
        $this->tasaCuota = $tasaCuota;
        $this->base = $base;
        $this->importe = $importe;
    }

    public static function buildKey(string $tipo, string $impuesto, string $tipoFactor, string $tasaCuota): string
    {
        if ('Retencion' === $tipo) {
            return sprintf('T:%s|I:%s', $tipo, $impuesto);
        }
        return sprintf('T:%s|I:%s|F:%s|C:%s', $tipo, $impuesto, $tipoFactor, $tasaCuota);
    }

    public function getKey(): string
    {
        return $this->buildKey($this->tipo, $this->impuesto, $this->tipoFactor, $this->tasaCuota);
    }

    public function getTipo(): string
    {
        return $this->tipo;
    }

    public function getImpuesto(): string
    {
        return $this->impuesto;
    }

    public function getTipoFactor(): string
    {
        return $this->tipoFactor;
    }

    public function getTasaCuota(): string
    {
        return $this->tasaCuota;
    }

    public function getBase(): Decimal
    {
        return $this->base;
    }

    public function getImporte(): Decimal
    {
        return $this->importe;
    }

    public function add(self $other): self
    {
        return new self(
            $this->tipo,
            $this->impuesto,
            $this->tipoFactor,
            $this->tasaCuota,
            $this->base->sum($other->base),
            $this->importe->sum($other->importe),
        );
    }

    public function truncate(int $decimals): self
    {
        return new self(
            $this->tipo,
            $this->impuesto,
            $this->tipoFactor,
            $this->tasaCuota,
            $this->base->truncate($decimals),
            $this->importe->truncate($decimals),
        );
    }

    public function multiply(Decimal $factor): self
    {
        return new self(
            $this->tipo,
            $this->impuesto,
            $this->tipoFactor,
            $this->tasaCuota,
            $this->base->multiply($factor),
            $this->importe->multiply($factor),
        );
    }

    public function divide(Decimal $factor): self
    {
        return new self(
            $this->tipo,
            $this->impuesto,
            $this->tipoFactor,
            $this->tasaCuota,
            $this->base->divide($factor),
            $this->importe->divide($factor),
        );
    }

    public function round(int $decimals): self
    {
        return new self(
            $this->tipo,
            $this->impuesto,
            $this->tipoFactor,
            $this->tasaCuota,
            $this->base->round($decimals),
            $this->importe->round($decimals),
        );
    }

    /** @return array<string, mixed> */
    public function jsonSerialize(): array
    {
        return [
            'tipo' => $this->tipo,
            'impuesto' => $this->impuesto,
            'tipoFactor' => $this->tipoFactor,
            'tasaCuota' => $this->tasaCuota,
            'base' => $this->base,
            'importe' => $this->importe,
        ];
    }
}
