<?php

namespace CfdiUtilsTests\ConsultaCfdiSat;

use CfdiUtils\ConsultaCfdiSat\Config;
use CfdiUtils\ConsultaCfdiSat\RequestParameters;
use CfdiUtils\ConsultaCfdiSat\StatusResponse;
use CfdiUtils\ConsultaCfdiSat\WebService;
use CfdiUtilsTests\TestCase;
use SoapClient;
use SoapFault;

/**
 * This test case is performing real request to SAT WebService.
 *
 * The problem is that since 2018-08 the service is failing on request,
 * and it makes the tests fail randomly.
 *
 * The workaround is to mark test skipped if we get a SoapFault when call
 * request or getSoapClient methods
 */
final class WebServiceConsumingTest extends TestCase
{
    private function createWebServiceObject(): WebService
    {
        $config = new Config(5, true, '');
        return new WebService($config);
    }

    private function tolerantRequest(RequestParameters $request): StatusResponse
    {
        $ws = $this->createWebServiceObject();
        try {
            return $ws->request($request);
        } catch (SoapFault $exception) {
            $this->markTestSkipped("SAT Service: {$exception->getMessage()}");
        }
    }

    private function tolerantSoapClient(WebService $ws): SoapClient
    {
        try {
            return $ws->getSoapClient();
        } catch (SoapFault $exception) {
            $this->markTestSkipped("SAT Service: {$exception->getMessage()}");
        }
    }

    public function testGetSoapClient(): void
    {
        $ws = $this->createWebServiceObject();

        $soapClient = $this->tolerantSoapClient($ws);
        $this->assertSame($soapClient, $this->tolerantSoapClient($ws));

        $ws->destroySoapClient();
        $this->assertNotSame($soapClient, $this->tolerantSoapClient($ws));
    }

    /** @requires PHP < 8.1 */
    public function testSoapClientHasSettings(): void
    {
        $config = new Config(60, false, '');
        $ws = new WebService($config);

        $soapClient = $this->tolerantSoapClient($ws);

        // check timeout
        /** @phpstan-ignore-next-line the variable is internal */
        $this->assertSame(60, $soapClient->{'_connection_timeout'});

        // check context
        /** @phpstan-ignore-next-line the variable is internal */
        $context = $soapClient->{'_stream_context'};
        $options = stream_context_get_options($context);
        $this->assertSame(false, $options['ssl']['verify_peer'] ?? null);
    }

    public function testValidDocumentVersion33(): void
    {
        $validCfdi33Request = new RequestParameters(
            '3.3',
            'POT9207213D6',
            'DIM8701081LA',
            '2010.01',
            'CEE4BE01-ADFA-4DEB-8421-ADD60F0BEDAC',
            '/OAgdg=='
        );

        $return = $this->tolerantRequest($validCfdi33Request);

        $this->assertStringStartsWith('S - ', $return->getCode());
        $this->assertSame('Vigente', $return->getCfdi());
        $this->assertSame('Cancelable con aceptación', $return->getCancellable());
        $this->assertSame('', $return->getCancellationStatus());
        $this->assertSame('200', $return->getValidationEfos());
    }

    public function testValidDocumentVersion32(): void
    {
        $validCfdi32Request = new RequestParameters(
            '3.2',
            'CTO021007DZ8',
            'XAXX010101000',
            '4685.00',
            '80824F3B-323E-407B-8F8E-40D83FE2E69F'
        );

        $return = $this->tolerantRequest($validCfdi32Request);

        $this->assertTrue($return->responseWasOk());
        $this->assertTrue($return->isVigente());
        $this->assertSame('Cancelable sin aceptación', $return->getCancellable());
        $this->assertSame('', $return->getCancellationStatus());
        $this->assertSame('200', $return->getValidationEfos());
    }

    public function testConsumeWebServiceWithNotFoundDocument(): void
    {
        $invalidCfdi33Request = new RequestParameters(
            '3.3',
            'POT9207213D6',
            'DIM8701081LA',
            '1010.01', // only change the first digit of the total
            'CEE4BE01-ADFA-4DEB-8421-ADD60F0BEDAC',
            '/OAgdg=='
        );

        $return = $this->tolerantRequest($invalidCfdi33Request);

        // N - 601: La expresión impresa proporcionada no es válida.
        $this->assertStringStartsWith('N - 601', $return->getCode());
        $this->assertStringStartsWith('No Encontrado', $return->getCfdi());
        $this->assertFalse($return->responseWasOk());
        $this->assertTrue($return->isNotFound());
        $this->assertFalse($return->isEfosListed());
    }

    public function testConsumeWebServiceWithCancelledDocument(): void
    {
        $invalidCfdi33Request = new RequestParameters(
            '3.3',
            'DIM8701081LA',
            'XEXX010101000',
            '8413.00',
            '3be40815-916c-4c91-84e2-6070d4bc3949',
            '...3f86Og=='
        );

        $return = $this->tolerantRequest($invalidCfdi33Request);

        $this->assertTrue($return->responseWasOk());
        $this->assertTrue($return->isCancelled());
        $this->assertFalse($return->isEfosListed());
    }
}
