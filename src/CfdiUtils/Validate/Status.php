<?php

namespace CfdiUtils\Validate;

/**
 * Status (immutable value object)
 * Define the status used in an assert
 */
class Status
{
    const STATUS_ERROR = 'ERROR';

    const STATUS_WARNING = 'WARN';

    const STATUS_NONE = 'NONE';

    const STATUS_OK = 'OK';

    const ORDER_MAP = [
        self::STATUS_ERROR => 1,
        self::STATUS_WARNING => 2,
        self::STATUS_NONE => 3,
        self::STATUS_OK => 4,
    ];

    private $status;

    public function __construct(string $value)
    {
        // using values as keys for speed access
        if (self::STATUS_ERROR !== $value && self::STATUS_WARNING !== $value
            && self::STATUS_OK !== $value && self::STATUS_NONE !== $value) {
            throw new \UnexpectedValueException('The status is not one of the defined valid constants');
        }
        $this->status = $value;
    }

    public static function ok(): self
    {
        return new self(self::STATUS_OK);
    }

    public static function error(): self
    {
        return new self(self::STATUS_ERROR);
    }

    public static function warn(): self
    {
        return new self(self::STATUS_WARNING);
    }

    public static function none(): self
    {
        return new self(self::STATUS_NONE);
    }

    public function isError(): bool
    {
        return self::STATUS_ERROR === $this->status;
    }

    public function isWarning(): bool
    {
        return self::STATUS_WARNING === $this->status;
    }

    public function isOk(): bool
    {
        return self::STATUS_OK === $this->status;
    }

    public function isNone(): bool
    {
        return self::STATUS_NONE === $this->status;
    }

    public static function when(bool $condition, self $errorStatus = null): self
    {
        return ($condition) ? self::ok() : ($errorStatus ? : self::error());
    }

    public function equalsTo(self $status): bool
    {
        return ($status->status === $this->status);
    }

    public function compareTo(self $status): int
    {
        return $this->comparableValue($this) <=> $this->comparableValue($status);
    }

    public static function comparableValue(self $status)
    {
        return self::ORDER_MAP[$status->status];
    }

    public function __toString()
    {
        return $this->status;
    }
}
