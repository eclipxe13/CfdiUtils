<?php

namespace CfdiUtils\SumasPagos20;

class Currencies
{
    /** @var array<string, int> */
    private ?array $currencyAllowedDecimals = null;

    /** @param array<string, int> $currencyAllowedDecimals */
    public function __construct(array $currencyAllowedDecimals)
    {
        foreach ($currencyAllowedDecimals as $currency => $decimals) {
            $this->currencyAllowedDecimals[$currency] = min(4, max(0, $decimals));
        }
    }

    public function get($currency): int
    {
        return $this->currencyAllowedDecimals[$currency] ?? 2;
    }
}
