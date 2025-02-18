<?php

namespace CfdiUtilsTests\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\Node;
use CfdiUtils\Validate\Cfdi33\Standard\EmisorRegimenFiscal;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\Validate\Validate33TestCase;

final class EmisorRegimenFiscalTest extends Validate33TestCase
{
    /** @var  EmisorRegimenFiscal */
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new EmisorRegimenFiscal();
    }

    public function providerValidCases(): array
    {
        return[
            // personas morales
            ['AAA010101AAA', '601'],
            ['AAA010101AAA', '603'],
            ['AAA010101AAA', '609'],
            ['AAA010101AAA', '610'],
            ['AAA010101AAA', '620'],
            ['AAA010101AAA', '622'],
            ['AAA010101AAA', '623'],
            ['AAA010101AAA', '624'],
            ['AAAA010101AA', '626'],
            ['AAA010101AAA', '628'],
            ['ÑAA010101AAA', '601'], // with Ñ
            // personas físicas
            ['AAAA010101AAA', '605'],
            ['AAAA010101AAA', '606'],
            ['AAAA010101AAA', '607'],
            ['AAAA010101AAA', '608'],
            ['AAAA010101AAA', '610'],
            ['AAAA010101AAA', '611'],
            ['AAAA010101AAA', '612'],
            ['AAAA010101AAA', '614'],
            ['AAAA010101AAA', '615'],
            ['AAAA010101AAA', '616'],
            ['AAAA010101AAA', '621'],
            ['AAAA010101AAA', '625'],
            ['AAAA010101AAA', '629'],
            ['AAAA010101AAA', '630'],
            ['AAAA010101AAA', '626'], // regimen RESICO
            ['AAAA010101AAA', '615'],
            ['ÑAAA010101AAA', '605'], // with Ñ
            ['AAA010000AAA', '601'], // RFC inválido, regimen válido persona moral
            ['AAAA010000AAA', '605'], // RFC inválido, regimen válido persona física
        ];
    }

    /**
     * @param string $emisorRfc
     * @param string $regimenFiscal
     * @dataProvider providerValidCases
     */
    public function testValidCases(string $emisorRfc, string $regimenFiscal): void
    {
        $this->comprobante->addChild(new Node('cfdi:Emisor', [
            'RegimenFiscal' => $regimenFiscal,
            'Rfc' => $emisorRfc,
        ]));

        $this->runValidate();
        $this->assertStatusEqualsCode(Status::ok(), 'REGFIS01');
    }

    public function providerInvalidCases(): array
    {
        return [
            ['AAA010101AAA', '605'], // persona moral con regimen incorrecto
            ['AAAA010101AAA', '601'], // persona física con regimen incorrecto
            ['', '615'], // RFC vacío, con regimen
            ['', ''], // RFC vacío, regimen vacío
            [null, ''], // sin RFC, regimen vacío
            [null, '630'], // sin RFC, con regimen
            [null, null],  // sin RFC, sin regimen
        ];
    }

    /**
     * @param string|null $emisorRfc
     * @param string|null $regimenFiscal
     * @dataProvider providerInvalidCases
     */
    public function testInvalidCases(?string $emisorRfc, ?string $regimenFiscal): void
    {
        $this->comprobante->addChild(new Node('cfdi:Emisor', [
            'RegimenFiscal' => $regimenFiscal,
            'Rfc' => $emisorRfc,
        ]));

        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'REGFIS01');
    }
}
