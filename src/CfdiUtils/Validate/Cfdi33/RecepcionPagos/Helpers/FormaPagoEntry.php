<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Helpers;

class FormaPagoEntry
{
    private string $key;

    private string $description;

    private string $senderAccountPattern;

    private string $receiverAccountPattern;

    public function __construct(
        string $key,
        string $description,
        private bool $allowSenderRfc,
        private bool $allowSenderAccount,
        string $senderAccountPattern,
        private bool $allowReceiverRfc,
        private bool $allowReceiverAccount,
        string $receiverAccountPattern,
        private bool $allowPaymentSignature,
    ) {
        if ('' === $key) {
            throw new \UnexpectedValueException('The FormaPago key cannot be empty');
        }
        if ('' === $description) {
            throw new \UnexpectedValueException('The FormaPago description cannot be empty');
        }
        $this->key = $key;
        $this->description = $description;
        $this->senderAccountPattern = $this->pattern($this->allowSenderAccount, $senderAccountPattern);
        $this->receiverAccountPattern = $this->pattern($this->allowReceiverAccount, $receiverAccountPattern);
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
