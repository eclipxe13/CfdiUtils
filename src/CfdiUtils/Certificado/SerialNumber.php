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
        $this->loadHexadecimal($hexString);
    }

    public function loadHexadecimal(string $hexString)
    {
        if (! (bool) preg_match('/^[0-9a-f]*$/', $hexString)) {
            throw new \UnexpectedValueException('The hexadecimal string contains invalid chars');
        }
        $this->hexString = $hexString;
    }

    public function loadDecimal(string $decString)
    {
        if (0 === strpos($decString, '0x') || 0 === strpos($decString, '0X')) {
            $hexString = substr($decString, 2);
        } else {
            $hexString = $this->baseConvert($decString, 10, 16);
        }
        $this->loadHexadecimal($hexString);
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
        return $this->baseConvert($this->getHexadecimal(), 16, 10);
    }

    protected function hexToAscii(string $input): string
    {
        return array_reduce(str_split($input, 2), function (string $carry, string $value): string {
            return $carry . chr(hexdec($value));
        }, '');
    }

    /**
     * Converts any string of any base to any other base without
     * PHP native method base_convert's double and float limitations.
     *
     * @param string $number The number to convert
     * @param int $frombase The base number is in
     * @param int $tobase The base to convert number to
     * @return string
     * @see https://php.net/base_convert
     * Original author: https://github.com/credomane/php_baseconvert
     */
    public function baseConvert(string $number, int $frombase, int $tobase): string
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyz';

        if (1 !== preg_match('/^[0-9a-zA-Z]+$/', $number)) {
            throw new \UnexpectedValueException('The number to convert is not valid alphanumeric');
        }
        if ($frombase < 2 || $frombase > 36) {
            throw new \UnexpectedValueException('Invalid base to convert from');
        }
        if ($tobase < 2 || $tobase > 36) {
            throw new \UnexpectedValueException('Invalid base to convert to');
        }

        $fromstring = substr($chars, 0, $frombase);
        if (1 !== preg_match("/^[$fromstring]+$/", $number)) {
            throw new \UnexpectedValueException('The number to convert contains invalid chars of base ' . $fromstring);
        }

        // early exit
        if ($tobase === $frombase) {
            return $number;
        }

        $length = strlen($number);
        $values = [];
        for ($i = 0; $i < $length; $i++) {
            $values[] = (int) stripos($fromstring, $number{$i});
        }

        $result = '';
        do {
            $divide = 0;
            $newlen = 0;
            for ($i = 0; $i < $length; $i++) {
                $divide = $divide * $frombase + $values[$i];
                if ($divide >= $tobase) {
                    $values[$newlen] = (int) ($divide / $tobase);
                    $divide = $divide % $tobase;
                    $newlen = $newlen + 1;
                } elseif ($newlen > 0) {
                    $values[$newlen] = 0;
                    $newlen = $newlen + 1;
                }
            }
            $length = $newlen;
            $result = $chars{$divide} . $result;
        } while ($newlen > 0);

        return $result;
    }
}
