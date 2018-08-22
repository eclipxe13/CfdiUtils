<?php
namespace CfdiUtilsTests\ConsultaCfdiSat;

use CfdiUtils\Cfdi;
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
            . '&tt=0000001234.567800'
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

    public function testCreateFromCfdiVersion32()
    {
        $cfdi = Cfdi::newFromString(file_get_contents($this->utilAsset('cfdi32-real.xml')));
        $parameters = RequestParameters::createFromCfdi($cfdi);

        $this->assertSame('3.2', $parameters->getVersion());
        $this->assertSame('CTO021007DZ8', $parameters->getRfcEmisor());
        $this->assertSame('XAXX010101000', $parameters->getRfcReceptor());
        $this->assertSame('80824F3B-323E-407B-8F8E-40D83FE2E69F', $parameters->getUuid());
        $this->assertStringEndsWith('YRbgmmVYiA==', $parameters->getSello());
        $this->assertEquals(4685.00, $parameters->getTotalFloat(), '', 0.001);
    }

    public function testCreateFromCfdiVersion33()
    {
        $cfdi = Cfdi::newFromString(file_get_contents($this->utilAsset('cfdi33-real.xml')));
        $parameters = RequestParameters::createFromCfdi($cfdi);

        $this->assertSame('3.3', $parameters->getVersion());
        $this->assertSame('POT9207213D6', $parameters->getRfcEmisor());
        $this->assertSame('DIM8701081LA', $parameters->getRfcReceptor());
        $this->assertSame('CEE4BE01-ADFA-4DEB-8421-ADD60F0BEDAC', $parameters->getUuid());
        $this->assertStringEndsWith('XmE4/OAgdg==', $parameters->getSello());
        $this->assertEquals(2010.01, $parameters->getTotalFloat(), '', 0.001);
    }

    /**
     * @param string $total
     * @param string $expected
     *
     * @testWith ["9.123456", "9.123456"]
     *           ["0.123456", "0.123456"]
     *           ["1", "1.0"]
     *           ["0.1", "0.1"]
     *           ["1.1", "1.1"]
     *           ["0", "0.0"]
     *           ["0.1234567", "0.123457"]
     *
     */
    public function testExpressionTotalExamples($total, $expected)
    {
        $parameters = new RequestParameters(
            '3.3',
            'AAA010101AAA',
            'COSC8001137NA',
            $total,
            'CEE4BE01-ADFA-4DEB-8421-ADD60F0BEDAC',
            '0123456789'
        );

        $this->assertContains('&tt=' . $expected . '&', $parameters->expression());
    }
}
