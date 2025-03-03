<?php

namespace CfdiUtils\SumasPagos20;

/**
 * The amounts on this class are set in the payment currency
 */
final class DoctoRelacionado
{
    public function __construct(private Decimal $impPagado, private Impuestos $impuestos)
    {
    }

    public function getImpPagado(): Decimal
    {
        return $this->impPagado;
    }

    public function getImpuestos(): Impuestos
    {
        return $this->impuestos;
    }
}
