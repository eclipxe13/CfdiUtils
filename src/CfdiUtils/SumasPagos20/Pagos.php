<?php

namespace CfdiUtils\SumasPagos20;

use JsonSerializable;

class Pagos implements JsonSerializable
{
    private Totales $totales;

    /** @var list<Pago> */
    private array $pagos;

    public function __construct(Totales $totales, Pago ...$pago)
    {
        $this->totales = $totales;
        $this->pagos = array_values($pago);
    }

    public function getTotales(): Totales
    {
        return $this->totales;
    }

    /** @return list<Pago> */
    public function getPagos(): array
    {
        return $this->pagos;
    }

    public function getPago(int $index): Pago
    {
        return $this->pagos[$index];
    }

    public function jsonSerialize(): array
    {
        return [
            'totales' => $this->totales,
            'pagos' => $this->pagos,
        ];
    }
}
