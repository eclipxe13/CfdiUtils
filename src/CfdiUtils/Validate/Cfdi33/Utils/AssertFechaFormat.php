<?php

namespace CfdiUtils\Validate\Cfdi33\Utils;

use CfdiUtils\Utils\Format;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Status;

class AssertFechaFormat
{
    public static function assertFormat(Asserts $asserts, string $code, string $label, string $text): bool
    {
        $hasFormat = static::hasFormat($text);
        $asserts->put(
            $code,
            sprintf('La fecha %s cumple con el formato', $label),
            Status::when($hasFormat),
            sprintf('Contenido del campo: "%s"', $text)
        );
        return $hasFormat;
    }

    public static function hasFormat(string $format): bool
    {
        if ('' === $format) {
            return false;
        }
        $value = (int) strtotime($format);
        $expecteFormat = Format::datetime($value);
        return ($expecteFormat === $format);
    }
}
