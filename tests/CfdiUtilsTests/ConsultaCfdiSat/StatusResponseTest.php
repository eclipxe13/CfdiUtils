<?php

namespace CfdiUtilsTests\ConsultaCfdiSat;

use CfdiUtils\ConsultaCfdiSat\StatusResponse;
use CfdiUtilsTests\TestCase;

final class StatusResponseTest extends TestCase
{
    public function testConsultaResponseExpectedOk()
    {
        $response = new StatusResponse(
            'S - Comprobante obtenido satisfactoriamente',
            'Vigente',
            'Cancelable con autorización',
            'En proceso',
            '200'
        );

        $this->assertSame('S - Comprobante obtenido satisfactoriamente', $response->getCode());
        $this->assertSame('Vigente', $response->getCfdi());
        $this->assertTrue($response->responseWasOk());
        $this->assertTrue($response->isVigente());
        $this->assertFalse($response->isNotFound());
        $this->assertFalse($response->isCancelled());
        $this->assertSame('Cancelable con autorización', $response->getCancellable());
        $this->assertSame('En proceso', $response->getCancellationStatus());
        $this->assertSame('200', $response->getValidationEfos());
        $this->assertFalse($response->isEfosListed());
    }

    public function testConsultaResponseNotOk()
    {
        $response = new StatusResponse(
            'N - 601: La expresión impresa proporcionada no es válida',
            'No Encontrado',
            '',
            '',
            '',
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
            'Cancelado',
            '',
            '',
            '',
        );

        $this->assertSame('S - Comprobante obtenido satisfactoriamente', $response->getCode());
        $this->assertSame('Cancelado', $response->getCfdi());
        $this->assertTrue($response->responseWasOk());
        $this->assertFalse($response->isVigente());
        $this->assertFalse($response->isNotFound());
        $this->assertTrue($response->isCancelled());
    }

    /**
     * @testWith ["200"]
     * @testWith ["201"]
     */
    public function testIsEfosListedOk(string $efosNotListedStatus)
    {
        $response = new StatusResponse(
            'S - Comprobante obtenido satisfactoriamente',
            'Vigente',
            'Cancelable con autorización',
            'En proceso',
            $efosNotListedStatus
        );

        $this->assertSame($efosNotListedStatus, $response->getValidationEfos());
        $this->assertFalse($response->isEfosListed());
    }

    public function testIsEfosListedNotOk()
    {
        $efosListedStatus = '100';
        $response = new StatusResponse(
            'S - Comprobante obtenido satisfactoriamente',
            'Vigente',
            'Cancelable con autorización',
            'En proceso',
            $efosListedStatus
        );

        $this->assertSame($efosListedStatus, $response->getValidationEfos());
        $this->assertTrue($response->isEfosListed());
    }
}
