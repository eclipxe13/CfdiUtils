<?php
namespace CfdiUtils\ConsultaCfdiSat;

use SoapClient;

class WebService
{
    /** @var SoapClient|null */
    private $soapClient;

    /** @var Config */
    private $config;

    public function __construct(Config $config = null)
    {
        $this->config = $config ? : new Config();
    }

    public function getConfig(): Config
    {
        return $this->config;
    }

    public function getSoapClient(): SoapClient
    {
        if (! $this->soapClient instanceof SoapClient) {
            $this->soapClient = $this->createSoapClient();
        }
        return $this->soapClient;
    }

    public function destroySoapClient()
    {
        $this->soapClient = null;
    }

    protected function createSoapClient(): SoapClient
    {
        $config = $this->getConfig();
        $soapOptions = [
            'soap_version' => SOAP_1_1,
            'cache_wsdl' => WSDL_CACHE_NONE,
            'exceptions' => 1,
            'stream_context' => stream_context_create([
                'ssl' => [
                    'verify_peer' => $config->shouldVerifyPeer(),
                ],
            ]),
            'connection_timeout' => $config->getTimeout(),
            'trace' => false, // use this setting for development
        ];

        return new SoapClient($config->getWsdlUrl(), $soapOptions);
    }

    public function request(RequestParameters $requestParameters): StatusResponse
    {
        $parameters = (object) [
            'expresionImpresa' => $requestParameters->expression(),
        ];
        $rawResponse = $this->doRequestConsulta($parameters);
        if (! ($rawResponse instanceof \stdClass)) {
            throw new \RuntimeException('The consulta web service did not return any result');
        }
        if (! isset($rawResponse->{'ConsultaResult'})) {
            throw new \RuntimeException('The consulta web service did not have expected ConsultaResult');
        }
        $result = (array) $rawResponse->{'ConsultaResult'};
        if (! isset($result['CodigoEstatus'])) {
            throw new \RuntimeException('The consulta web service did not have expected ConsultaResult:CodigoEstatus');
        }
        if (! isset($result['Estado'])) {
            throw new \RuntimeException('The consulta web service did not have expected ConsultaResult:Estado');
        }
        return new StatusResponse($result['CodigoEstatus'], $result['Estado']);
    }

    /**
     * @param \stdClass $parameters
     * @return null|\stdClass
     */
    protected function doRequestConsulta(\stdClass $parameters)
    {
        return $this->getSoapClient()->{'Consulta'}($parameters);
    }
}
