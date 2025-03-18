<?php

namespace CfdiUtils\SumasPagos20;

use JsonSerializable;

final class Pago implements JsonSerializable
{
    public function __construct(
        private Decimal $monto,
        private Decimal $montoMinimo,
        private Decimal $tipoCambioP,
        private Impuestos $impuestos,
    ) {
    }

    public function getMonto(): Decimal
    {
        return $this->monto;
    }

    public function getMontoMinimo(): Decimal
    {
        return $this->montoMinimo;
    }

    public function getTipoCambioP(): Decimal
    {
        return $this->tipoCambioP;
    }

    public function getImpuestos(): Impuestos
    {
        return $this->impuestos;
    }

    /** @return array<string, mixed> */
    public function jsonSerialize(): array
    {
        return [
            'monto' => $this->monto,
            'montoMinimo' => $this->montoMinimo,
            'tipoCambioP' => $this->tipoCambioP,
            'impuestos' => $this->impuestos,
        ];
    }
}
