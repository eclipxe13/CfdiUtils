<?php

namespace CfdiUtils\OpenSSL;

class PemContainer
{
    public function __construct(private string $certificate, private string $publicKey, private string $privateKey)
    {
    }

    public function certificate(): string
    {
        return $this->certificate;
    }

    public function publicKey(): string
    {
        return $this->publicKey;
    }

    public function privateKey(): string
    {
        return $this->privateKey;
    }

    public function hasAny(): bool
    {
        return $this->hasCertificate() || $this->hasPublicKey() || $this->hasPrivateKey();
    }

    public function hasCertificate(): bool
    {
        return ('' !== $this->certificate);
    }

    public function hasPublicKey(): bool
    {
        return ('' !== $this->publicKey);
    }

    public function hasPrivateKey(): bool
    {
        return ('' !== $this->privateKey);
    }
}
