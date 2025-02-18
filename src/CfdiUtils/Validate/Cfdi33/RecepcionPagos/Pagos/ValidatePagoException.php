<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Validate\Status;

class ValidatePagoException extends \Exception
{
    private ?Status $status = null;

    public function getStatus(): Status
    {
        return $this->status ?: Status::error();
    }

    public function setStatus(Status $status): self
    {
        $this->status = $status;
        return $this;
    }
}
