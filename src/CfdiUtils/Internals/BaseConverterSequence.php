<?php

namespace CfdiUtils\Internals;

/**
 * This is a value object for BaseConverter containing the sequence
 *
 * NOTE: Changes will not be considering a bracking compatibility change since this utility is for internal usage only
 * @internal
 */
class BaseConverterSequence implements \Stringable
{
    private string $sequence;

    private int $length;

    public function __construct(string $sequence)
    {
        self::checkIsValid($sequence);

        $this->sequence = $sequence;
        $this->length = strlen($sequence);
    }

    public function __toString(): string
    {
        return $this->sequence;
    }

    public function value(): string
    {
        return $this->sequence;
    }

    public function length(): int
    {
        return $this->length;
    }

    public static function isValid(string $value): bool
    {
        try {
            static::checkIsValid($value);
            return true;
        } catch (\UnexpectedValueException) {
            return false;
        }
    }

    public static function checkIsValid(string $sequence): void
    {
        $length = strlen($sequence);

        // is not empty
        if ($length < 2) {
            throw new \UnexpectedValueException('Sequence does not contains enough elements');
        }

        if ($length !== mb_strlen($sequence)) {
            throw new \UnexpectedValueException('Cannot use multibyte strings in dictionary');
        }

        $valuesCount = array_count_values(str_split(strtoupper($sequence)));
        $repeated = array_filter($valuesCount, fn (int $count): bool => 1 !== $count);
        if ([] !== $repeated) {
            throw new \UnexpectedValueException(
                sprintf('The sequence has not unique values: "%s"', implode(', ', array_keys($repeated)))
            );
        }
    }
}
