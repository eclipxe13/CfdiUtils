<?php

namespace CfdiUtils\ConsultaCfdiSat;

use RuntimeException;
use SoapClient;
use SoapVar;
use stdClass;

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
        /*
         * options.location: required to build the object
         *
         * options.uri: required to build the object
         *
         * options.use: SOAP_ENCODED (default) or SOAP_LITERAL
         * Both works but SOAP_LITERAL is cleaner
         *
         * options.style: SOAP_RPC (default) or SOAP_DOCUMENT
         * SOAP_DOCUMENT removes the method name from soap body
         *
         */
        $config = $this->getConfig();
        $soapOptions = [
            'location' => $config->getServiceUrl(),
            'uri' => 'http://tempuri.org/',
            'style' => SOAP_RPC,
            'use' => SOAP_LITERAL,
            'soap_version' => SOAP_1_1,
            'exceptions' => 1,
            'stream_context' => stream_context_create([
                'ssl' => [
                    'verify_peer' => $config->shouldVerifyPeer(),
                ],
            ]),
            'connection_timeout' => $config->getTimeout(),
            'trace' => false, // use this setting for development
        ];

        return new SoapClient(null, $soapOptions);
    }

    public function request(RequestParameters $requestParameters): StatusResponse
    {
        $rawResponse = $this->doRequestConsulta($requestParameters->expression());

        if (! ($rawResponse instanceof stdClass)) {
            throw new RuntimeException('The consulta web service did not return any result');
        }
        $result = (array) $rawResponse;
        if (! isset($result['CodigoEstatus'])) {
            throw new RuntimeException('The consulta web service did not have expected ConsultaResult:CodigoEstatus');
        }
        if (! isset($result['Estado'])) {
            throw new RuntimeException('The consulta web service did not have expected ConsultaResult:Estado');
        }
        return new StatusResponse(
            $result['CodigoEstatus'],
            $result['Estado'],
            $result['EsCancelable'] ?? '',
            $result['EstatusCancelacion'] ?? '',
            $result['ValidacionEFOS'] ?? ''
        );
    }

    /**
     * This method exists to be able to mock SOAP call
     *
     * @param string $expression
     * @return null|stdClass
     *@internal
     */
    protected function doRequestConsulta(string $expression): ?stdClass
    {
        /** @var int $encoding Override because inspectors does not know that second argument can be NULL */
        $encoding = null;
        return $this->getSoapClient()->__soapCall(
            'Consulta',
            [new SoapVar($expression, $encoding, '', '', 'expresionImpresa', 'http://tempuri.org/')],
            ['soapaction' => 'http://tempuri.org/IConsultaCFDIService/Consulta']
        );
    }
}
