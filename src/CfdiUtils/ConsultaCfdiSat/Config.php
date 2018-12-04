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
    private $serviceUrl;

    /** @var string */
    private $wsdlLocation;

    public function __construct(
        int $timeout = 10,
        bool $verifyPeer = true,
        string $serviceUrl = '',
        string $wsdlLocation = ''
    ) {
        $this->timeout = $timeout;
        $this->verifyPeer = $verifyPeer;
        $this->serviceUrl = $serviceUrl ? : static::DEFAULT_WSDL_URL;
        $this->wsdlLocation = $wsdlLocation ? : $this->serviceUrl;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    public function shouldVerifyPeer(): bool
    {
        return $this->verifyPeer;
    }

    /**
     * @deprecated since Version 2.7.1 in favor of getServiceUrl
     * @see getServiceUrl
     * @return string
     */
    public function getWsdlUrl(): string
    {
        return $this->getServiceUrl();
    }

    public function getServiceUrl(): string
    {
        return $this->serviceUrl;
    }

    public function getWsdlLocation(): string
    {
        return $this->wsdlLocation;
    }

    public static function getLocalWsdlLocation(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'ConsultaCFDIServiceSAT.svc.xml';
    }
}
