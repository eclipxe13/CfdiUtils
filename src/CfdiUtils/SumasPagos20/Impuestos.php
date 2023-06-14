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

    /** @param array<string, Impuesto> $impuestos */
    private static function withImpuestos(array $impuestos): self
    {
        $object = new self();
        $object->impuestos = $impuestos;
        return $object;
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

    /** @return list<Impuesto> */
    public function getTraslados(): array
    {
        return $this->filterByTipo('Traslado');
    }

    public function getTraslado(string $impuesto, string $tipoFactor, string $tasaCuota): Impuesto
    {
        return $this->get('Traslado', $impuesto, $tipoFactor, $tasaCuota);
    }

    /** @return list<Impuesto> */
    public function getRetenciones(): array
    {
        return $this->filterByTipo('Retencion');
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
        return self::withImpuestos($impuestos);
    }

    public function truncate(int $decimals): self
    {
        $impuestos = $this->impuestos;
        foreach ($impuestos as $key => $impuesto) {
            $impuestos[$key] = $impuesto->truncate($decimals);
        }
        return self::withImpuestos($impuestos);
    }

    public function multiply(Decimal $value): self
    {
        $impuestos = $this->impuestos;
        foreach ($impuestos as $key => $impuesto) {
            $impuestos[$key] = $impuesto->multiply($value);
        }
        return self::withImpuestos($impuestos);
    }

    public function round(int $decimals): self
    {
        $impuestos = $this->impuestos;
        foreach ($impuestos as $key => $impuesto) {
            $impuestos[$key] = $impuesto->round($decimals);
        }
        return self::withImpuestos($impuestos);
    }

    /** @return array<string, Impuesto> */
    public function jsonSerialize(): array
    {
        return $this->impuestos;
    }

    /** @return list<Impuesto> */
    private function filterByTipo(string $tipo): array
    {
        return array_values(array_filter(
            $this->impuestos,
            function (Impuesto $impuesto) use ($tipo): bool {
                return $impuesto->getTipo() === $tipo;
            }
        ));
    }
}
