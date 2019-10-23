<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\DoctoRelacionado;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\AbstractPagoValidator;

abstract class AbstractDoctoRelacionadoValidator extends AbstractPagoValidator
{
    /** @var NodeInterface */
    private $pago;

    /** @var int */
    private $index;

    /**
     * @param NodeInterface $docto
     * @return bool
     */
    abstract public function validateDoctoRelacionado(NodeInterface $docto): bool;

    public function exception(string $message): ValidateDoctoException
    {
        $exception = new ValidateDoctoException($message);
        $exception->setIndex($this->getIndex());
        $exception->setValidatorCode($this->getCode());
        return $exception;
    }

    public function validatePago(NodeInterface $pago): bool
    {
        throw new \LogicException('This method must not be called');
    }

    public function getPago(): NodeInterface
    {
        return $this->pago;
    }

    public function setPago(NodeInterface $pago)
    {
        $this->pago = $pago;
    }

    public function getIndex(): int
    {
        return $this->index;
    }

    public function setIndex(int $index)
    {
        $this->index = $index;
    }
}
