<?php
namespace CfdiUtils\Certificado;

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
        $this->hexString = $hexString;
    }

    public function loadHexadecimal(string $hexString)
    {
        $this->hexString = $hexString;
    }

    public function loadDecimal(string $decString)
    {
        $this->loadHexadecimal($this->bcDectoHex($decString));
    }

    public function getHexadecimal(): string
    {
        return $this->hexString;
    }

    public function asAscii(): string
    {
        return $this->hexToAscii($this->getHexadecimal());
    }

    protected function hexToAscii(string $input): string
    {
        return array_reduce(str_split($input, 2), function (string $carry, string $value): string {
            return $carry . chr(hexdec($value));
        }, '');
    }

    /**
     * Return a big decimal to hexadecimal
     *
     * source: https://stackoverflow.com/questions/14539727/how-to-convert-a-huge-integer-to-hex-in-php
     * author: https://stackoverflow.com/users/1341059/lafor
     *
     * @param string $dec
     * @return string
     */
    protected function bcDectoHex(string $dec): string
    {
        // is string is already an hex string (prefixed with 0x)
        if (0 === strpos($dec, '0x')) {
            return substr($dec, 2);
        }

        // convert to hex (non-recursive)
        $hex = '';
        do {
            $last = bcmod($dec, 16);
            $hex = dechex((int) $last) . $hex;
            $dec = bcdiv(bcsub($dec, $last), 16);
        } while ($dec > 0);
        return $hex;
    }
}
