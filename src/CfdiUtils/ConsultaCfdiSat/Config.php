<?php

namespace CfdiUtils\ConsultaCfdiSat;

class Config
{
    /**
     * Default value of SAT web service
     * @var string
     */
    public const DEFAULT_SERVICE_URL = 'https://consultaqr.facturaelectronica.sat.gob.mx/ConsultaCFDIService.svc';

    private string $serviceUrl;

    public function __construct(
        private int $timeout = 10,
        private bool $verifyPeer = true,
        string $serviceUrl = '',
    ) {
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
