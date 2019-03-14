<?php
namespace CfdiUtils\Certificado;

use CfdiUtils\Utils\Internal\BaseConverter;

/**
 * This class is used to load hexadecimal or decimal data as a certificate serial number.
 * It have its own class because SOLID and is easy to test in this way.
 * It is not intented to use in general.
 */
class SerialNumber
{
    /** @var string Hexadecimal representation */
    private $hexString;

    public function __construct(string $hexString)
    {
        $this->loadHexadecimal($hexString);
    }

    public function loadHexadecimal(string $hexString)
    {
        if (! (bool) preg_match('/^[0-9a-f]*$/', $hexString)) {
            throw new \UnexpectedValueException('The hexadecimal string contains invalid characters');
        }
        $this->hexString = $hexString;
    }

    public function loadDecimal(string $decString)
    {
        if (0 === strpos($decString, '0x') || 0 === strpos($decString, '0X')) {
            $hexString = substr($decString, 2);
        } else {
            $hexString = BaseConverter::createBase36()->convert($decString, 10, 16);
        }
        $this->loadHexadecimal($hexString);
    }

    public function loadAscii(string $input)
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
        return array_reduce(str_split($input, 2), function (string $carry, string $value): string {
            return $carry . chr(intval(hexdec($value)));
        }, '');
    }

    protected function asciiToHex(string $input): string
    {
        return array_reduce(str_split($input, 1), function (string $carry, string $value): string {
            return $carry . dechex(ord($value));
        }, '');
    }

    /**
     * @param string $number
     * @param int $frombase
     * @param int $tobase
     * @return string
     * @deprecated since 2.8.1
     */
    public function baseConvert(string $number, int $frombase, int $tobase): string
    {
        trigger_error('This method is deprecated, should not be used from outside this class');
        return BaseConverter::createBase36()->convert($number, $frombase, $tobase);
    }
}
