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
        if ($value !== self::STATUS_ERROR && $value !== self::STATUS_WARNING
            && $value !== self::STATUS_OK && $value !== self::STATUS_NONE) {
            throw new \UnexpectedValueException('The status is not one of the defined valid constants');
        }
        $this->status = $value;
    }

    public static function ok(): Status
    {
        return new self(self::STATUS_OK);
    }

    public static function error(): Status
    {
        return new self(self::STATUS_ERROR);
    }

    public static function warn(): Status
    {
        return new self(self::STATUS_WARNING);
    }

    public static function none(): Status
    {
        return new self(self::STATUS_NONE);
    }

    public function isError(): bool
    {
        return $this->status === self::STATUS_ERROR;
    }

    public function isWarning(): bool
    {
        return $this->status === self::STATUS_WARNING;
    }

    public function isOk(): bool
    {
        return $this->status === self::STATUS_OK;
    }

    public function isNone(): bool
    {
        return $this->status === self::STATUS_NONE;
    }

    public static function when(bool $condition, Status $errorStatus = null): Status
    {
        return ($condition) ? self::ok() : ($errorStatus ? : self::error());
    }

    public function equalsTo(Status $status): bool
    {
        return ($status->status === $this->status);
    }

    public function compareTo(Status $status): int
    {
        return $this->comparableValue($this) <=> $this->comparableValue($status);
    }

    public static function comparableValue(Status $status)
    {
        return self::ORDER_MAP[$status->status];
    }

    public function __toString()
    {
        return $this->status;
    }
}
