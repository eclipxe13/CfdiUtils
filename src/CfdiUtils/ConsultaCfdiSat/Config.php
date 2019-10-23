<?php

namespace CfdiUtils\ConsultaCfdiSat;

class Config
{
    /**
     * Default value of SAT web service
     * @var string
     */
    const DEFAULT_SERVICE_URL = 'https://consultaqr.facturaelectronica.sat.gob.mx/ConsultaCFDIService.svc';

    /**
     * This library does not use WSDL anymore
     *
     * @deprecated :3.0.0
     * @see self::DEFAULT_SERVICE_URL
     * @var string
     */
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
        $this->serviceUrl = $serviceUrl ? : static::DEFAULT_SERVICE_URL;
        $this->wsdlLocation = $wsdlLocation;
        if ('' !== $this->wsdlLocation) {
            trigger_error(__CLASS__ . ' deprecated WSDL location', E_USER_DEPRECATED);
        }
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

    /**
     * @deprecated 2.10.0:3.0.0 WebService does not require WSDL anymore
     * @return string
     */
    public function getWsdlLocation(): string
    {
        trigger_error(__METHOD__ . ' is deprecated since WebService does not require WSDL anymore', E_USER_DEPRECATED);
        return $this->wsdlLocation;
    }

    /**
     * @deprecated 2.10.0:3.0.0 Service does not require WSDL anymore
     * @return string
     */
    public static function getLocalWsdlLocation(): string
    {
        trigger_error(__METHOD__ . ' is deprecated since WebService does not require WSDL anymore', E_USER_DEPRECATED);
        return __DIR__ . DIRECTORY_SEPARATOR . 'ConsultaCFDIServiceSAT.svc.xml';
    }
}
