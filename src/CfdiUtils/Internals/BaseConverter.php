<?php

namespace CfdiUtils\Internals;

/**
 * Converts any string of any base to any other base without
 * PHP native method base_convert's double and float limitations.
 *
 * @see https://php.net/base_convert
 * Original author: https://github.com/credomane/php_baseconvert
 *
 * NOTE: Changes will not be considering a bracking compatibility change since this utility is for internal usage only
 * @internal
 */
class BaseConverter
{
    /** @var BaseConverterSequence */
    private $sequence;

    public function __construct(BaseConverterSequence $sequence)
    {
        $this->sequence = $sequence;
    }

    public static function createBase36(): self
    {
        return new self(new BaseConverterSequence('0123456789abcdefghijklmnopqrstuvwxyz'));
    }

    public function sequence(): BaseConverterSequence
    {
        return $this->sequence;
    }

    public function maximumBase(): int
    {
        return $this->sequence->length();
    }

    public function convert(string $input, int $frombase, int $tobase): string
    {
        if ($frombase < 2 || $frombase > $this->maximumBase()) {
            throw new \UnexpectedValueException('Invalid from base');
        }
        if ($tobase < 2 || $tobase > $this->maximumBase()) {
            throw new \UnexpectedValueException('Invalid to base');
        }

        $originalSequence = $this->sequence()->value();
        if ('' === $input) {
            $input = $originalSequence[0]; // use zero as input
        }
        $chars = substr($originalSequence, 0, $frombase);
        if (! boolval(preg_match("/^[$chars]+$/", $input))) {
            throw new \UnexpectedValueException('The number to convert contains invalid characters');
        }

        $length = strlen($input);
        $values = [];
        for ($i = 0; $i < $length; $i++) {
            $values[] = intval(stripos($originalSequence, $input[$i]));
        }

        $result = '';
        do {
            $divide = 0;
            $newlen = 0;
            for ($i = 0; $i < $length; $i++) {
                $divide = $divide * $frombase + $values[$i];
                if ($divide >= $tobase) {
                    $values[$newlen] = intval($divide / $tobase);
                    $divide = $divide % $tobase;
                    $newlen = $newlen + 1;
                } elseif ($newlen > 0) {
                    $values[$newlen] = 0;
                    $newlen = $newlen + 1;
                }
            }
            $length = $newlen;
            $result = $originalSequence[$divide] . $result;
        } while ($newlen > 0);

        return $result;
    }
}
