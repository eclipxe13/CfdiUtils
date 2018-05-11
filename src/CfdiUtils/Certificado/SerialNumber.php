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
            throw new \UnexpectedValueException("The hexadecimal string contains invalid chars");
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

    protected function hexToAscii(string $input): string
    {
        return array_reduce(str_split($input, 2), function (string $carry, string $value): string {
            return $carry . chr(hexdec($value));
        }, '');
    }

    /**
     * Converts any string of any base to any other base without PHP native method
     * base_convert's double and float limitations.
     *
     * @param string $numstring
     * @param int $frombase
     * @param int $tobase
     * @return string
     * @source https://github.com/credomane/php_baseconvert
     */
    public function baseConvert(string $numstring, int $frombase, int $tobase): string
    {
        $numstring = strtolower($numstring);
        if (1 !== preg_match('/^[0-9a-z]+$/', $numstring)) {
            throw new \UnexpectedValueException('The number to convert is not valid alphanumeric');
        }
        if ($frombase < 2 || $frombase > 36) {
            throw new \UnexpectedValueException('Invalid base to convert from');
        }
        if ($tobase < 2 || $tobase > 36) {
            throw new \UnexpectedValueException('Invalid base to convert to');
        }
        if ($tobase === $frombase) {
            return $numstring;
        }
        $chars = "0123456789abcdefghijklmnopqrstuvwxyz";
        $fromstring = substr($chars, 0, $frombase);
        $tostring = substr($chars, 0, $tobase);
        $length = strlen($numstring);
        $values = array_map(function ($char) use ($frombase, $fromstring) {
            if (false === $number = strpos($fromstring, $char)) {
                throw new \UnexpectedValueException(sprintf(
                    'The number to convert contains a character %s that is out of base %d',
                    $char,
                    $frombase
                ));
            }
            return $number;
        }, str_split($numstring));

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
            $result = $tostring{$divide} . $result;
        } while ($newlen > 0);

        return $result;
    }
}
