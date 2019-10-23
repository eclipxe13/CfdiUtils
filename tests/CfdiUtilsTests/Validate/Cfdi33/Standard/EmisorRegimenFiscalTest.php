<?php

namespace CfdiUtilsTests\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\Node;
use CfdiUtils\Validate\Cfdi33\Standard\EmisorRegimenFiscal;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\Validate\ValidateTestCase;

class EmisorRegimenFiscalTest extends ValidateTestCase
{
    /** @var  EmisorRegimenFiscal */
    protected $validator;

    protected function setUp()
    {
        parent::setUp();
        $this->validator = new EmisorRegimenFiscal();
    }

    public function providerValidCases()
    {
        return[
            // personas morales
            ['AAA010101AAA', '601'],
            ['OEKLGO048LSA', '603'],
            ['OEKLGO048LSA', '609'],
            ['AAA010101AAA', '610'],
            ['DJEIG04KGILD', '620'],
            ['DJEIG04KGILD', '622'],
            ['KKGO40FKIWG0', '623'],
            ['KKWEIGJ34978', '624'],
            ['LLROR04KKG94', '628'],
            ['SLLWIO9034LK', '607'],
            // personas físicas
            ['AAA010101AAAA', '605'],
            ['OEKLGO048LSAS', '606'],
            ['DJEIG04KGILDD', '608'],
            ['OEKLGO048LSAS', '610'],
            ['KKGO40FKIWG0S', '611'],
            ['KKWEIGJ34978G', '612'],
            ['LLROR04KKG94Q', '614'],
            ['SLLWIO9034LKG', '616'],
            ['LSIKIEG03KGIE', '621'],
            ['KKGO40FKIWG0S', '622'],
            ['LLSORGJ3098G8', '629'],
            ['IOEUGKI49G49J', '630'],
            ['JJJSID98KF0SL', '615'],
        ];
    }

    /**
     * @param string $emisorRfc
     * @param string $regimenFiscal
     * @dataProvider providerValidCases
     */
    public function testValidCases($emisorRfc, $regimenFiscal)
    {
        $this->comprobante->addChild(new Node('cfdi:Emisor', [
            'RegimenFiscal' => $regimenFiscal,
            'Rfc' => $emisorRfc,
        ]));

        $this->runValidate();
        $this->assertStatusEqualsCode(Status::ok(), 'REGFIS01');
    }

    public function providerInvalidCases()
    {
        return [
            ['AAA010101AAA', '605'],
            ['OEKLGO048LSAS', '601'],
            ['', '615'],
            ['', ''],
            [null, ''],
            [null, '630'],
            [null, null],
        ];
    }

    /**
     * @param string $emisorRfc
     * @param string $regimenFiscal
     * @dataProvider providerInvalidCases
     */
    public function testInvalidCases($emisorRfc, $regimenFiscal)
    {
        $this->comprobante->addChild(new Node('cfdi:Emisor', [
            'RegimenFiscal' => $regimenFiscal,
            'Rfc' => $emisorRfc,
        ]));

        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'REGFIS01');
    }
}
