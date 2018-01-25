<?php
namespace CfdiUtilsTests\ConsultaCfdiSat;

use CfdiUtils\ConsultaCfdiSat\RequestParameters;
use CfdiUtilsTests\TestCase;

class RequestParametersTest extends TestCase
{
    public function testConstructorAndGetters()
    {
        $parameters = new RequestParameters(
            '3.3',
            'AAA010101AAA',
            'COSC8001137NA',
            '1,234.5678',
            'CEE4BE01-ADFA-4DEB-8421-ADD60F0BEDAC',
            '0123456789'
        );
        $this->assertSame('3.3', $parameters->getVersion());
        $this->assertSame('AAA010101AAA', $parameters->getRfcEmisor());
        $this->assertSame('COSC8001137NA', $parameters->getRfcReceptor());
        $this->assertSame('1,234.5678', $parameters->getTotal());
        $this->assertEquals(1234.5678, $parameters->getTotalFloat(), '', 0.0000001);
        $this->assertSame('CEE4BE01-ADFA-4DEB-8421-ADD60F0BEDAC', $parameters->getUuid());
        $this->assertSame('0123456789', $parameters->getSello());

        $expected33 = 'https://verificacfdi.facturaelectronica.sat.gob.mx/default.aspx'
            . '?id=CEE4BE01-ADFA-4DEB-8421-ADD60F0BEDAC'
            . '&re=AAA010101AAA'
            . '&rr=COSC8001137NA'
            . '&tt=1234.5678'
            . '&fe=23456789';

        $this->assertSame($expected33, $parameters->expression());

        $expected32 = ''
            . '?re=AAA010101AAA'
            . '&rr=COSC8001137NA'
            . '&tt=000000001234.5678'
            . '&id=CEE4BE01-ADFA-4DEB-8421-ADD60F0BEDAC';
        $parameters->setVersion('3.2');
        $this->assertSame($expected32, $parameters->expression());
    }

    public function testConstructorWithWrongVersion()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('version');

        new RequestParameters(
            '1.1',
            'AAA010101AAA',
            'COSC8001137NA',
            '1,234.5678',
            'CEE4BE01-ADFA-4DEB-8421-ADD60F0BEDAC',
            '0123456789'
        );
    }
}
