<?php

namespace CfdiUtils\Certificado;

use CfdiUtils\Internals\BaseConverter;

/**
 * This class is used to load hexadecimal or decimal data as a certificate serial number.
 * It have its own class because SOLID and is easy to test in this way.
 * It is not intented to use in general.
 */
class SerialNumber
{
    /** @var string Hexadecimal representation */
    private string $hexString;

    public function __construct(string $hexString)
    {
        $this->loadHexadecimal($hexString);
    }

    public function loadHexadecimal(string $hexString): void
    {
        if (! preg_match('/^[0-9a-f]*$/', $hexString)) {
            throw new \UnexpectedValueException('The hexadecimal string contains invalid characters');
        }
        $this->hexString = $hexString;
    }

    public function loadDecimal(string $decString): void
    {
        if (0 === strcasecmp('0x', substr($decString, 0, 2))) {
            $hexString = substr($decString, 2);
        } else {
            $hexString = BaseConverter::createBase36()->convert($decString, 10, 16);
        }
        $this->loadHexadecimal($hexString);
    }

    public function loadAscii(string $input): void
    {
        $this->loadHexadecimal($this->asciiToHex($input));
    }

    public function getHexadecimal(): string
    {
        return $this->hexString;
    }

    public function asAscii(): string
    {
        return $this->hexToAscii($this->getHexadecimal());
    }

    public function asDecimal(): string
    {
        return BaseConverter::createBase36()->convert($this->getHexadecimal(), 16, 10);
    }

    protected function hexToAscii(string $input): string
    {
        return implode('', array_map(
            fn (string $value): string => chr(intval(hexdec($value))),
            str_split($input, 2),
        ));
    }

    protected function asciiToHex(string $input): string
    {
        return implode('', array_map(
            fn (string $value): string => dechex(ord($value)),
            str_split($input, 1),
        ));
    }
}
