<?php
namespace CfdiUtilsTests;

use CfdiUtils\Cfdi;

class CfdiTest extends TestCase
{
    public function testNewFromStringWithEmptyXml()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('empty');
        Cfdi::newFromString('');
    }

    public function testNewFromStringWithInvalidXml()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Cannot create a DOM Document');
        Cfdi::newFromString(' ');
    }

    public function testConstructWithoutNamespace()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('namespace http://www.sat.gob.mx/cfd/3');
        Cfdi::newFromString('<Comprobante version="3.2"' . '/>');
    }

    public function testInvalidCfdiRootIsNotComprobante()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Root element is not Comprobante');

        $checker = Cfdi::newFromString(
            '<cfdi:X xmlns:cfdi="http://www.sat.gob.mx/cfd/3" version="3.2"' . '/>'
        );

        $this->assertEquals('', $checker->getVersion());
    }

    public function testInvalidCfdiRootIsNotPrefixed()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Root element is not Comprobante');

        $checker = Cfdi::newFromString(
            '<x:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" version="3.2"' . '/>'
        );

        $this->assertEquals('', $checker->getVersion());
    }

    public function testValid32()
    {
        $checker = Cfdi::newFromString(
            '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" version="3.2"' . '/>'
        );

        $this->assertEquals('3.2', $checker->getVersion());
    }

    public function testValid33()
    {
        $checker = Cfdi::newFromString(
            '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" Version="3.3"' . '/>'
        );

        $this->assertEquals('3.3', $checker->getVersion());
    }

    public function testValid33WithXmlHeader()
    {
        $checker = Cfdi::newFromString(
            '<?xml version="1.0" encoding="UTF-8" ?>'
            . '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" Version="3.3"' . '/>'
        );

        $this->assertEquals('3.3', $checker->getVersion());
    }

    public function testVersion1980ReturnEmpty()
    {
        $checker = Cfdi::newFromString(
            '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" Version="1.9.80"' . '/>'
        );

        $this->assertEmpty($checker->getVersion());
    }

    public function testVersionEmptyReturnEmpty()
    {
        $checker = Cfdi::newFromString(
            '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" Version=""' . '/>'
        );

        $this->assertEmpty($checker->getVersion());
    }
}
