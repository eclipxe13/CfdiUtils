<?php
namespace CfdiUtils\ConsultaCfdiSat;

class StatusResponse
{
    /** @var string */
    private $code;

    /** @var string */
    private $cfdi;

    public function __construct(string $statusCode, string $status)
    {
        $this->code = $statusCode;
        $this->cfdi = $status;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getCfdi(): string
    {
        return $this->cfdi;
    }

    public function responseWasOk(): bool
    {
        return ('S - ' === substr($this->code, 0, 4));
    }

    public function isVigente(): bool
    {
        return ('Vigente' === $this->cfdi);
    }

    public function isNotFound(): bool
    {
        return ('No Encontrado' === $this->cfdi);
    }

    public function isCancelled(): bool
    {
        return ('Cancelado' === $this->cfdi);
    }
}
