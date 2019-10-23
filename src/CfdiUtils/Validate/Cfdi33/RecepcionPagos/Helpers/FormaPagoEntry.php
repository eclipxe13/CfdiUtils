<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Helpers;

class FormaPagoEntry
{
    /** @var string */
    private $key;

    /** @var string */
    private $description;

    /** @var bool */
    private $allowSenderRfc;

    /** @var bool */
    private $allowSenderAccount;

    /** @var string */
    private $senderAccountPattern;

    /** @var bool */
    private $allowReceiverRfc;

    /** @var bool */
    private $allowReceiverAccount;

    /** @var string */
    private $receiverAccountPattern;

    /** @var bool */
    private $allowPaymentSignature;

    public function __construct(
        string $key,
        string $description,
        bool $allowSenderRfc,
        bool $allowSenderAccount,
        string $senderAccountPattern,
        bool $allowReceiverRfc,
        bool $allowReceiverAccount,
        string $receiverAccountPattern,
        bool $allowPaymentSignature
    ) {
        if ('' === $key) {
            throw new \UnexpectedValueException('The FormaPago key cannot be empty');
        }
        if ('' === $description) {
            throw new \UnexpectedValueException('The FormaPago description cannot be empty');
        }
        $this->key = $key;
        $this->description = $description;
        $this->allowSenderRfc = $allowSenderRfc;
        $this->allowSenderAccount = $allowSenderAccount;
        $this->senderAccountPattern = $this->pattern($allowSenderAccount, $senderAccountPattern);
        $this->allowReceiverRfc = $allowReceiverRfc;
        $this->allowReceiverAccount = $allowReceiverAccount;
        $this->receiverAccountPattern = $this->pattern($allowReceiverAccount, $receiverAccountPattern);
        $this->allowPaymentSignature = $allowPaymentSignature;
    }

    private function pattern(bool $allowed, string $pattern): string
    {
        if (! $allowed || '' === $pattern) {
            return '/^$/';
        }

        return $pattern;
    }

    public function key(): string
    {
        return $this->key;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function allowSenderRfc(): bool
    {
        return $this->allowSenderRfc;
    }

    public function allowSenderAccount(): bool
    {
        return $this->allowSenderAccount;
    }

    public function senderAccountPattern(): string
    {
        return $this->senderAccountPattern;
    }

    public function allowReceiverRfc(): bool
    {
        return $this->allowReceiverRfc;
    }

    public function allowReceiverAccount(): bool
    {
        return $this->allowReceiverAccount;
    }

    public function receiverAccountPattern(): string
    {
        return $this->receiverAccountPattern;
    }

    public function allowPaymentSignature(): bool
    {
        return $this->allowPaymentSignature;
    }
}
