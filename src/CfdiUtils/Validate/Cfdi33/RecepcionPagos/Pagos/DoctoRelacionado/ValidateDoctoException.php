<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado;

use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\ValidatePagoException;

class ValidateDoctoException extends ValidatePagoException
{
    private ?int $index = null;

    private ?string $validatorCode = null;

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
