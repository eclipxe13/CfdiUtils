<?php

namespace CfdiUtilsTests\ConsultaCfdiSat;

use CfdiUtils\ConsultaCfdiSat\StatusResponse;
use CfdiUtilsTests\TestCase;

class StatusResponseTest extends TestCase
{
    public function testConsultaResponseExpectedOk()
    {
        $response = new StatusResponse(
            'S - Comprobante obtenido satisfactoriamente',
            'Vigente'
        );

        $this->assertSame('S - Comprobante obtenido satisfactoriamente', $response->getCode());
        $this->assertSame('Vigente', $response->getCfdi());
        $this->assertTrue($response->responseWasOk());
        $this->assertTrue($response->isVigente());
        $this->assertFalse($response->isNotFound());
        $this->assertFalse($response->isCancelled());
    }

    public function testConsultaResponseNotOk()
    {
        $response = new StatusResponse(
            'N - 601: La expresión impresa proporcionada no es válida',
            'No Encontrado'
        );

        $this->assertSame('N - 601: La expresión impresa proporcionada no es válida', $response->getCode());
        $this->assertSame('No Encontrado', $response->getCfdi());
        $this->assertFalse($response->responseWasOk());
        $this->assertFalse($response->isVigente());
        $this->assertTrue($response->isNotFound());
        $this->assertFalse($response->isCancelled());
    }

    public function testConsultaResponseCancelled()
    {
        $response = new StatusResponse(
            'S - Comprobante obtenido satisfactoriamente',
            'Cancelado'
        );

        $this->assertSame('S - Comprobante obtenido satisfactoriamente', $response->getCode());
        $this->assertSame('Cancelado', $response->getCfdi());
        $this->assertTrue($response->responseWasOk());
        $this->assertFalse($response->isVigente());
        $this->assertFalse($response->isNotFound());
        $this->assertTrue($response->isCancelled());
    }
}
