<?php

namespace CfdiUtils\Utils;

class CurrencyDecimals
{
    /** @var string */
    private $currency;

    /** @var int */
    private $decimals;

    public function __construct(string $currency, int $decimals)
    {
        if (! preg_match('/^[A-Z]{3}$/', $currency)) {
            throw new \UnexpectedValueException('Property currency is not valid');
        }
        if ($decimals < 0) {
            throw new \UnexpectedValueException('Property decimals cannot be less than zero');
        }
        $this->currency = $currency;
        $this->decimals = $decimals;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function decimals(): int
    {
        return $this->decimals;
    }

    public function round(float $value): float
    {
        return round($value, $this->decimals());
    }

    public function doesNotExceedDecimals(string $value): bool
    {
        // use pathinfo trick to retrieve the right part after the dot
        return ($this->decimalsCount($value) <= $this->decimals());
    }

    public static function decimalsCount(string $value): int
    {
        return strlen(pathinfo($value, PATHINFO_EXTENSION));
    }

    public static function newFromKnownCurrencies(string $currency, int $default = null): self
    {
        $decimals = static::knownCurrencyDecimals($currency);
        if ($decimals < 0) {
            if (null === $default) {
                throw new \OutOfBoundsException('The currency %s is not known');
            }
            $decimals = $default;
        }
        return new self($currency, $decimals);
    }

    public static function knownCurrencyDecimals(string $currency): int
    {
        $map = [
            'MXN' => 2,
            'EUR' => 2,
            'USD' => 2,
            'XXX' => 0,
        ];
        return array_key_exists($currency, $map) ? $map[$currency] : -1;
    }
}
