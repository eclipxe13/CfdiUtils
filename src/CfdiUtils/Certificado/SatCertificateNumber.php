<?php

namespace CfdiUtils\Certificado;

class SatCertificateNumber
{
    /** @var string */
    private $id;

    public function __construct(string $id)
    {
        if (! $this->isValidCertificateNumber($id)) {
            throw new \UnexpectedValueException('The certificate number is not correct');
        }
        $this->id = $id;
    }

    public function number(): string
    {
        return $this->id;
    }

    public function remoteUrl(): string
    {
        return sprintf(
            'https://rdc.sat.gob.mx/rccf/%s/%s/%s/%s/%s/%s.cer',
            substr($this->id, 0, 6),
            substr($this->id, 6, 6),
            substr($this->id, 12, 2),
            substr($this->id, 14, 2),
            substr($this->id, 16, 2),
            $this->id
        );
    }

    public static function isValidCertificateNumber(string $id): bool
    {
        return (bool) preg_match('/^[0-9]{20}$/', $id);
    }
}
