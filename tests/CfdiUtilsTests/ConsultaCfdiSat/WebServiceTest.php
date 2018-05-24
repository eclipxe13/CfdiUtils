<?php
namespace CfdiUtilsTests\ConsultaCfdiSat;

use CfdiUtils\ConsultaCfdiSat\Config;
use CfdiUtils\ConsultaCfdiSat\RequestParameters;
use CfdiUtils\ConsultaCfdiSat\WebService;
use CfdiUtilsTests\TestCase;

class WebServiceTest extends TestCase
{
    public function testConstructWithNoConfig()
    {
        $config = new Config();
        $ws = new WebService();
        $this->assertEquals($config, $ws->getConfig());
        $this->assertNotSame($config, $ws->getConfig());
    }

    public function testConstructWithConfig()
    {
        $config = new Config(60, false);
        $ws = new WebService($config);
        $this->assertSame($config, $ws->getConfig());
    }

    public function testGetSoapClient()
    {
        $config = new Config(60, false);
        $ws = new WebService($config);

        $soapClient = $ws->getSoapClient();
        $this->assertSame($soapClient, $ws->getSoapClient());

        $ws->destroySoapClient();
        $this->assertNotSame($soapClient, $ws->getSoapClient());
    }

    public function testSoapClientHasSettings()
    {
        $config = new Config(60, false);
        $ws = new WebService($config);

        $soapClient = $ws->getSoapClient();

        // check timeout
        $this->assertSame(60, $soapClient->{'_connection_timeout'});

        // check context
        $context = $soapClient->{'_stream_context'};
        $options = stream_context_get_options($context);
        $this->assertArraySubset(['ssl' => ['verify_peer' => false]], $options);
    }

    public function testValidDocumentVersion33()
    {
        $validCfdi33Request = new RequestParameters(
            '3.3',
            'POT9207213D6',
            'DIM8701081LA',
            '2010.01',
            'CEE4BE01-ADFA-4DEB-8421-ADD60F0BEDAC',
            '/OAgdg=='
        );

        $ws = new WebService();
        $return = $ws->request($validCfdi33Request);

        $this->assertTrue($return->responseWasOk());
        $this->assertTrue($return->isVigente());
    }

    public function testValidDocumentVersion32()
    {
        $validCfdi32Request = new RequestParameters(
            '3.2',
            'CTO021007DZ8',
            'XAXX010101000',
            '4685.00',
            '80824F3B-323E-407B-8F8E-40D83FE2E69F'
        );

        $ws = new WebService();
        $return = $ws->request($validCfdi32Request);

        $this->assertTrue($return->responseWasOk());
        $this->assertTrue($return->isVigente());
    }

    public function testConsumeWebServiceWithNotFoundDocument()
    {
        $invalidCfdi33Request = new RequestParameters(
            '3.3',
            'POT9207213D6',
            'DIM8701081LA',
            '1010.01', // only change the first digit of the total
            'CEE4BE01-ADFA-4DEB-8421-ADD60F0BEDAC',
            '/OAgdg=='
        );

        $ws = new WebService();
        $return = $ws->request($invalidCfdi33Request);

        // N - 601: La expresión impresa proporcionada no es válida.
        $this->assertStringStartsWith('N - 601', $return->getCode());
        $this->assertStringStartsWith('No Encontrado', $return->getCfdi());
        $this->assertFalse($return->responseWasOk());
        $this->assertTrue($return->isNotFound());
    }

    public function testConsumeWebServiceWithCancelledDocument()
    {
        $invalidCfdi33Request = new RequestParameters(
            '3.3',
            'DIM8701081LA',
            'XEXX010101000',
            '8413.00',
            '3be40815-916c-4c91-84e2-6070d4bc3949',
            '...3f86Og=='
        );

        $ws = new WebService();
        $return = $ws->request($invalidCfdi33Request);

        $this->assertTrue($return->responseWasOk());
        $this->assertTrue($return->isCancelled());
    }

    public function providerRequestWithBadRawResponse()
    {
        return [
            'response invalid' => [
                null,
                'The consulta web service did not return any result',
            ],
            'ConsultaResult missing' => [
                (object) [],
                'The consulta web service did not have expected ConsultaResult',
            ],
            'ConsultaResult:CodigoEstatus missing' => [
                (object) ['ConsultaResult' => []],
                'The consulta web service did not have expected ConsultaResult:CodigoEstatus',
            ],
            'ConsultaResult:Estado missing' => [
                (object) ['ConsultaResult' => ['CodigoEstatus' => '']],
                'The consulta web service did not have expected ConsultaResult:Estado',
            ],
            'No exception' => [
                (object) ['ConsultaResult' => ['CodigoEstatus' => '', 'Estado' => '']],
                '',
            ],
        ];
    }

    /**
     * @param \stdClass|null $rawResponse
     * @param string $expectedMessage
     * @dataProvider providerRequestWithBadRawResponse
     */
    public function testRequestWithBadRawResponse($rawResponse, string $expectedMessage)
    {
        /** @var WebService|\PHPUnit\Framework\MockObject\MockObject $mock */
        $mock = $this->getMockBuilder(WebService::class)
            ->setMethodsExcept(['request'])
            ->setMethods(['doRequestConsulta'])
            ->getMock();
        // expects once as constraint because maybe $expectedMessage is empty
        $mock->expects($this->once())->method('doRequestConsulta')->willReturn($rawResponse);

        $validCfdi33Request = new RequestParameters(
            '3.3',
            'POT9207213D6',
            'DIM8701081LA',
            '2010.01',
            'CEE4BE01-ADFA-4DEB-8421-ADD60F0BEDAC',
            '/OAgdg=='
        );

        if ('' !== $expectedMessage) {
            $this->expectException(\RuntimeException::class);
            $this->expectExceptionMessage($expectedMessage);
        }

        $mock->request($validCfdi33Request);
    }
}
