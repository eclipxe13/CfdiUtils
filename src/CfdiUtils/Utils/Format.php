<?php

namespace CfdiUtils\Utils;

/**
 * This class provides static methods to format the values of the attributes
 */
class Format
{
    public static function number(float $value, $decimals = 2): string
    {
        return number_format($value, $decimals, '.', '');
    }

    public static function datetime(int $timestamp): string
    {
        return date('Y-m-d\TH:i:s', $timestamp);
    }
}
