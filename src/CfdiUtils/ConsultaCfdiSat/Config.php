<?php
namespace CfdiUtils\ConsultaCfdiSat;

class Config
{
    const DEFAULT_WSDL_URL = 'https://consultaqr.facturaelectronica.sat.gob.mx/ConsultaCFDIService.svc?singleWsdl';

    /** @var int */
    private $timeout;

    /** @var bool */
    private $verifyPeer;

    /** @var string */
    private $wsdlUrl;

    public function __construct(int $timeout = 10, bool $verifyPeer = true, string $wsdlUrl = '')
    {
        $this->timeout = $timeout;
        $this->verifyPeer = $verifyPeer;
        $this->wsdlUrl = $wsdlUrl ? : static::DEFAULT_WSDL_URL;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    public function shouldVerifyPeer(): bool
    {
        return $this->verifyPeer;
    }

    public function getWsdlUrl(): string
    {
        return $this->wsdlUrl;
    }
}
