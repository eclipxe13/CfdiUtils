<?php

namespace CfdiUtils\ConsultaCfdiSat;

class Config
{
    /**
     * Default value of SAT web service
     * @var string
     */
    public const DEFAULT_SERVICE_URL = 'https://consultaqr.facturaelectronica.sat.gob.mx/ConsultaCFDIService.svc';

    private int $timeout;

    private bool $verifyPeer;

    private string $serviceUrl;

    public function __construct(
        int $timeout = 10,
        bool $verifyPeer = true,
        string $serviceUrl = ''
    ) {
        $this->timeout = $timeout;
        $this->verifyPeer = $verifyPeer;
        $this->serviceUrl = $serviceUrl ?: static::DEFAULT_SERVICE_URL;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    public function shouldVerifyPeer(): bool
    {
        return $this->verifyPeer;
    }

    public function getServiceUrl(): string
    {
        return $this->serviceUrl;
    }
}
