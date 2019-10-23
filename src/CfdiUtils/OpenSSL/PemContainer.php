<?php

namespace CfdiUtils\OpenSSL;

class PemContainer
{
    /** @var string */
    private $certificate;

    /** @var string */
    private $publicKey;

    /** @var string */
    private $privateKey;

    public function __construct(string $certificate, string $publicKey, string $privateKey)
    {
        $this->certificate = $certificate;
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
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
