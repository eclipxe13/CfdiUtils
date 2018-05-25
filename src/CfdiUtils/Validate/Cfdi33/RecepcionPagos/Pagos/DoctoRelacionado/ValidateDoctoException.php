<?php
namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado;

class ValidateDoctoException extends \Exception
{
    /** @var int */
    private $index;

    /** @var string */
    private $validatorCode;

    public function setIndex(int $index): self
    {
        $this->index = $index;
        return $this;
    }

    public function setValidatorCode(string $validatorCode): self
    {
        $this->validatorCode = $validatorCode;
        return $this;
    }

    public function getIndex(): int
    {
        return $this->index;
    }

    public function getValidatorCode(): string
    {
        return $this->validatorCode;
    }
}
