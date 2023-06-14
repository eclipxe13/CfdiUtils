<?php

namespace CfdiUtils\SumasPagos20;

/**
 * The amounts on this class are set in the payment currency
 */
final class DoctoRelacionado
{
    /** @var Decimal */
    private $impPagado;

    /** @var Impuestos */
    private $impuestos;

    public function __construct(Decimal $impPagado, Impuestos $impuestos)
    {
        $this->impPagado = $impPagado;
        $this->impuestos = $impuestos;
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
