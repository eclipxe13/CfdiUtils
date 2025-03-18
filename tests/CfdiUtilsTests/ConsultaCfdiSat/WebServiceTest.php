<?php

namespace CfdiUtilsTests\ConsultaCfdiSat;

use CfdiUtils\ConsultaCfdiSat\Config;
use CfdiUtils\ConsultaCfdiSat\RequestParameters;
use CfdiUtils\ConsultaCfdiSat\WebService;
use CfdiUtilsTests\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

final class WebServiceTest extends TestCase
{
    public function testConstructWithNoConfig(): void
    {
        $config = new Config();
        $ws = new WebService();
        $this->assertEquals($config, $ws->getConfig());
        $this->assertNotSame($config, $ws->getConfig());
    }

    public function testConstructWithConfig(): void
    {
        $config = new Config(60, false);
        $ws = new WebService($config);
        $this->assertSame($config, $ws->getConfig());
    }

    public function providerRequestWithBadRawResponse(): array
    {
        return [
            'response invalid' => [
                null,
                'The consulta web service did not return any result',
            ],
            'CodigoEstatus missing' => [
                (object) [],
                'The consulta web service did not have expected ConsultaResult:CodigoEstatus',
            ],
            'ConsultaResult:Estado missing' => [
                (object) ['CodigoEstatus' => ''],
                'The consulta web service did not have expected ConsultaResult:Estado',
            ],
            'No exception' => [
                (object) ['CodigoEstatus' => '', 'Estado' => ''],
                '',
            ],
        ];
    }

    /**
     * @dataProvider providerRequestWithBadRawResponse
     */
    public function testRequestWithBadRawResponse(?\stdClass $rawResponse, string $expectedMessage): void
    {
        /** @var WebService&MockObject $webService */
        $webService = $this->getMockBuilder(WebService::class)
            ->setMethodsExcept(['request', 'requestExpression'])
            ->setMethods(['doRequestConsulta'])
            ->getMock();
        // expects once as constraint because maybe $expectedMessage is empty
        $webService->expects($this->once())->method('doRequestConsulta')->willReturn($rawResponse);

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

        $webService->request($validCfdi33Request);
    }
}
