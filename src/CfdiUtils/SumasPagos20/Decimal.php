<?php

/** @noinspection PhpComposerExtensionStubsInspection */

namespace CfdiUtils\SumasPagos20;

use JsonSerializable;

final class Decimal implements JsonSerializable
{
    const SCALE = 24;

    /** @var string */
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function sum(self $other, int $scale = self::SCALE): self
    {
        return new self(bcadd($this->value, $other->value, $scale));
    }

    public function multiply(self $other, int $scale = self::SCALE): self
    {
        return new self(bcmul($this->value, $other->value, $scale));
    }

    public function divide(self $other, int $scale = self::SCALE): self
    {
        return new self(bcdiv($this->value, $other->value, $scale));
    }

    public function round(int $decimals): self
    {
        $exp = bcpow('10', strval($decimals + 1));
        $offset = (bccomp($this->value, '0', $decimals) < 0) ? '-5' : '5';
        return new self(bcdiv(bcadd(bcmul($this->value, $exp, 0), $offset), $exp, $decimals));
    }

    public function truncate(int $decimals): self
    {
        return new self(bcadd($this->value, '0', $decimals));
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }

    public function compareTo(self $other): int
    {
        return bccomp($this->value, $other->value, self::SCALE);
    }
}
