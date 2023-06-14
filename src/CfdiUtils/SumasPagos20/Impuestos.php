<?php

namespace CfdiUtils\SumasPagos20;

use JsonSerializable;
use LogicException;

final class Impuestos implements JsonSerializable
{
    /** @var array<string, Impuesto> */
    private $impuestos = [];

    public function __construct(Impuesto ...$impuestos)
    {
        foreach ($impuestos as $impuesto) {
            $this->impuestos[$impuesto->getKey()] = $impuesto;
        }
    }

    public function find(string $tipo, string $impuesto, string $tipoFactor = '', string $tasaCuota = ''): ?Impuesto
    {
        $key = Impuesto::buildKey($tipo, $impuesto, $tipoFactor, $tasaCuota);
        if (! isset($this->impuestos[$key])) {
            return null;
        }
        return $this->impuestos[$key];
    }

    public function get(string $tipo, string $impuesto, string $tipoFactor = '', string $tasaCuota = ''): Impuesto
    {
        $impuesto = $this->find($tipo, $impuesto, $tipoFactor, $tasaCuota);
        if (null === $impuesto) {
            throw new LogicException(sprintf(
                'No se pudo encontrar el %s impuesto "%s", tipo factor "%s", tasa o cuota "%s"',
                $tipo,
                $impuesto,
                $tipoFactor,
                $tasaCuota
            ));
        }
        return $impuesto;
    }

    public function getTraslado(string $impuesto, string $tipoFactor, string $tasaCuota): Impuesto
    {
        return $this->get('Traslado', $impuesto, $tipoFactor, $tasaCuota);
    }

    /** @return Impuesto[] */
    public function getRetenciones(): array
    {
        return $this->filterByTipo('Retencion');
    }

    /** @return Impuesto[] */
    public function getTraslados(): array
    {
        return $this->filterByTipo('Traslado');
    }

    /** @return Impuesto[] */
    private function filterByTipo(string $tipo): array
    {
        return array_values(array_filter(
            $this->impuestos,
            function (Impuesto $impuesto) use ($tipo): bool {
                return $impuesto->getTipo() === $tipo;
            }
        ));
    }

    public function getRetencion(string $impuesto): Impuesto
    {
        return $this->get('Retencion', $impuesto);
    }

    public function aggregate(self $other): self
    {
        $impuestos = $this->impuestos;
        foreach ($other->impuestos as $key => $impuesto) {
            $impuestos[$key] = (isset($impuestos[$key])) ? $impuesto->add($impuestos[$key]) : $impuesto;
        }
        return new self(...$impuestos);
    }

    public function truncate(int $decimals): self
    {
        $impuestos = $this->impuestos;
        foreach ($impuestos as $key => $impuesto) {
            $impuestos[$key] = $impuesto->truncate($decimals);
        }
        return new self(...$impuestos);
    }

    public function multiply(Decimal $value): self
    {
        $impuestos = $this->impuestos;
        foreach ($impuestos as $key => $impuesto) {
            $impuestos[$key] = $impuesto->multiply($value);
        }
        return new self(...$impuestos);
    }

    public function round(int $decimals): self
    {
        $impuestos = $this->impuestos;
        foreach ($impuestos as $key => $impuesto) {
            $impuestos[$key] = $impuesto->round($decimals);
        }
        return new self(...$impuestos);
    }

    /** @return array<string, Impuesto> */
    public function jsonSerialize(): array
    {
        return $this->impuestos;
    }
}
