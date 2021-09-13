<?php

namespace CfdiUtils\Certificado;

use PhpCfdi\Credentials\SerialNumber;
use UnexpectedValueException;

class SatCertificateNumber
{
    /** @var SerialNumber */
    private $serialNumber;

    public function __construct(SerialNumber $serialNumber)
    {
        if (! $this->isValidCertificateNumber($serialNumber->bytes())) {
            throw new UnexpectedValueException('The certificate number is not correct');
        }
        $this->serialNumber = $serialNumber;
    }

    public static function newFromString(string $number): self
    {
        $serialNumber = SerialNumber::createFromBytes($number);
        return new self($serialNumber);
    }

    public function number(): string
    {
        return $this->serialNumber->bytes();
    }

    public function object(): SerialNumber
    {
        return $this->serialNumber;
    }

    public function remoteUrl(): string
    {
        $serialNumber = $this->number();
        return sprintf(
            'https://rdc.sat.gob.mx/rccf/%s/%s/%s/%s/%s/%s.cer',
            substr($serialNumber, 0, 6),
            substr($serialNumber, 6, 6),
            substr($serialNumber, 12, 2),
            substr($serialNumber, 14, 2),
            substr($serialNumber, 16, 2),
            $serialNumber
        );
    }

    public static function isValidCertificateNumber(string $number): bool
    {
        return (bool) preg_match('/^[0-9]{20}$/', $number);
    }
}
