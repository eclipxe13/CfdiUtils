<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Utils\CurrencyDecimals;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Helpers\FormaPagoCatalog;
use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Helpers\FormaPagoEntry;
use CfdiUtils\Validate\Status;

abstract class AbstractPagoValidator
{
    /** @var string */
    protected $code = '';

    /** @var string */
    protected $title = '';

    public function getCode()
    {
        return $this->code;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function registerInAssets(Asserts $asserts)
    {
        $asserts->put($this->getCode(), $this->getTitle(), Status::ok());
    }

    /**
     * In this method is where all validations must occur
     *
     * @param NodeInterface $pago
     * @throws ValidatePagoException then validation fails
     * @throws \Exception in the implementer if it does not return TRUE
     * @return true|bool
     */
    abstract public function validatePago(NodeInterface $pago): bool;

    protected function isGreaterThan(float $value, float $compare): bool
    {
        return ($value - $compare > 0.0000001);
    }

    protected function isEqual(float $expected, float $value): bool
    {
        return (abs($expected - $value) < 0.0000001);
    }

    protected function createCurrencyDecimals(string $currency): CurrencyDecimals
    {
        try {
            return CurrencyDecimals::newFromKnownCurrencies($currency);
        } catch (\Throwable $exception) {
            return new CurrencyDecimals($currency ?: 'XXX', 0);
        }
    }

    protected function createPaymentType(string $paymentType): FormaPagoEntry
    {
        try {
            return (new FormaPagoCatalog())->obtain($paymentType);
        } catch (\Throwable $exception) {
            throw new ValidatePagoException(sprintf('La forma de pago "%s" no está definida', $paymentType));
        }
    }
}
