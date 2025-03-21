<?php

declare(strict_types=1);

namespace CfdiUtils\Development\ElementsMaker;

final class Dictionary
{
    /** @param array<string, string> $values */
    public function __construct(private array $values)
    {
    }

    public function get(string $key): string
    {
        return $this->values[$key] ?? '';
    }

    public function with(string $key, string $value): self
    {
        return new self(array_merge($this->values, [$key => $value]));
    }

    /** @return array<string, string> $values */
    public function getValues(): array
    {
        return $this->values;
    }

    public function interpolate(string $subject): string
    {
        return strtr($subject, $this->values);
    }
}
