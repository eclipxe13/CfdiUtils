<?php

namespace CfdiUtils\Utils;

class Rfc
{
    const RFC_GENERIC = 'XAXX010101000';

    const RFC_FOREIGN = 'XEXX010101000';

    const DISALLOW_GENERIC = 1;

    const DISALLOW_FOREIGN = 2;

    /** @var string */
    private $rfc;

    /** @var int */
    private $length;

    /** @var string contains calculated checksum */
    private $checkSum;

    /** @var bool */
    private $checkSumMatch;

    public function __construct(string $rfc, int $flags = 0)
    {
        $this->checkIsValid($rfc, $flags);
        $this->rfc = $rfc;
        $this->length = mb_strlen($rfc);
        $this->checkSum = static::obtainCheckSum($rfc);
        $this->checkSumMatch = ($this->checkSum === strval(substr($rfc, -1)));
    }

    public function rfc(): string
    {
        return $this->rfc;
    }

    public function isPerson(): bool
    {
        return (13 === $this->length);
    }

    public function isMoral(): bool
    {
        return (12 === $this->length);
    }

    public function isGeneric(): bool
    {
        return (static::RFC_GENERIC === $this->rfc);
    }

    public function isForeign(): bool
    {
        return (static::RFC_FOREIGN === $this->rfc);
    }

    public function checkSum(): string
    {
        return $this->checkSum;
    }

    public function checkSumMatch(): bool
    {
        return $this->checkSumMatch;
    }

    public function __toString(): string
    {
        return $this->rfc();
    }

    public static function isValid(string $value): bool
    {
        try {
            static::checkIsValid($value);
            return true;
        } catch (\UnexpectedValueException $exception) {
            return false;
        }
    }

    /**
     * @param string $value
     * @param int $flags
     * @throws \UnexpectedValueException when the value is generic and is not allowed by flags
     * @throws \UnexpectedValueException when the value is foreign and is not allowed by flags
     * @throws \UnexpectedValueException when the value does not match with the RFC format
     * @throws \UnexpectedValueException when the date inside the value is not valid
     * @throws \UnexpectedValueException when the last digit does not match the checksum
     */
    public static function checkIsValid(string $value, int $flags = 0)
    {
        if ($flags & static::DISALLOW_GENERIC && $value === static::RFC_GENERIC) {
            throw new \UnexpectedValueException('No se permite el RFC genérico para público en general');
        }
        if ($flags & static::DISALLOW_FOREIGN && $value === static::RFC_FOREIGN) {
            throw new \UnexpectedValueException('No se permite el RFC genérico para operaciones con extranjeros');
        }
        // validate agains a regular expression (values and length)
        $regex = '/^' // desde el inicio
            . '[A-ZÑ&]{3,4}' // letras y números para el nombre (3 para morales, 4 para físicas)
            . '([0-9]{6})' // año mes y día, la validez de la fecha se comprueba después
            . '[A-Z0-9]{2}[A0-9]{1}' // homoclave (letra o dígito 2 veces + A o dígito 1 vez)
            . '$/u'; // hasta el final, considerar la cadena unicode
        if (1 !== preg_match($regex, $value)) {
            throw new \UnexpectedValueException('No coincide el formato de un RFC');
        }
        if (0 === static::obtainDate($value)) {
            throw new \UnexpectedValueException('La fecha obtenida no es lógica');
        }
    }

    public static function obtainCheckSum(string $rfc): string
    {
        // 'Ñ' translated to '#' due it is multibyte 0xC3 0xB1
        $dictionary = array_flip(str_split('0123456789ABCDEFGHIJKLMN&OPQRSTUVWXYZ #', 1));
        $chars = str_split(str_replace('Ñ', '#', $rfc), 1);
        array_pop($chars); // remove predefined checksum
        $length = count($chars);
        $sum = (11 === $length) ? 481 : 0; // 481 para morales, 0 para físicas
        $j = $length + 1;
        foreach ($chars as $i => $char) {
            $sum += ($dictionary[$char] ?? 0) * ($j - $i);
        }
        $digit = strval(11 - $sum % 11);
        if ('11' === $digit) {
            $digit = '0';
        } elseif ('10' === $digit) {
            $digit = 'A';
        }
        return $digit;
    }

    /**
     * The date is always from the year 2000 since RFC does not provide century and 000229 is valid.
     * Please, change this function on year 2100!
     *
     * @param string $rfc
     * @return int
     */
    public static function obtainDate(string $rfc): int
    {
        // rfc is multibyte
        $begin = (12 === mb_strlen($rfc)) ? 3 : 4;
        // strdate is not multibyte
        $strdate = strval(mb_substr($rfc, $begin, 6));
        if (6 !== strlen($strdate)) {
            return 0;
        }
        $parts = str_split($strdate, 2);
        // year 2000 is leap year (%4 & %100 & %400)
        /** @var int|false $date phpstan does not know that mktime can return false */
        $date = mktime(0, 0, 0, (int) $parts[1], (int) $parts[2], (int) ('20' . $parts[0]));
        if (false === $date) {
            /** @codeCoverageIgnore it is unlikely to enter this block */
            return 0;
        }
        if (date('ymd', $date) !== $strdate) {
            return 0;
        }
        return $date;
    }
}
