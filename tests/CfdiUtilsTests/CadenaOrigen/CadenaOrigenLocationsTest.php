<?php
namespace CfdiUtilsTests\CadenaOrigen;

use CfdiUtils\CadenaOrigen\CadenaOrigenLocations;
use CfdiUtils\CadenaOrigen\DefaultLocations;
use CfdiUtilsTests\TestCase;

class CadenaOrigenLocationsTest extends TestCase
{
    public function testGetXsltLocationDefault()
    {
        $co = new CadenaOrigenLocations();
        $this->assertEmpty($co->getXsltLocation('1.0'));
        $this->assertEquals(DefaultLocations::XSLT_32, $co->getXsltLocation('3.2'));
        $this->assertEquals(DefaultLocations::XSLT_33, $co->getXsltLocation('3.3'));
    }

    public function testGetXsltLocationsDefault()
    {
        $co = new CadenaOrigenLocations();
        $locations = $co->getXsltLocations();
        $this->assertArrayHasKey('3.2', $locations);
        $this->assertArrayHasKey('3.3', $locations);
        $this->assertCount(2, $locations);
    }

    public function testSetXsltLocationsDefault()
    {
        $co = new CadenaOrigenLocations();
        $changedLocation = '/foo/bar';
        $co->setXsltLocation('3.2', $changedLocation);
        $this->assertSame($changedLocation, $co->getXsltLocation('3.2'));
        $co->setXsltLocation('1.2', $changedLocation);
        $this->assertSame($changedLocation, $co->getXsltLocation('1.2'));
        $this->assertCount(3, $co->getXsltLocations());
    }

    public function testGetXsltLocationFromXml()
    {
        $co = new CadenaOrigenLocations();
        $this->assertSame(
            DefaultLocations::XSLT_32,
            $co->getXsltLocationFromXml('<cfdi:Comprobante version="3.2" xmlns:cfdi="http://www.sat.gob.mx/cfd/3"/>')
        );
        $this->assertSame(
            DefaultLocations::XSLT_33,
            $co->getXsltLocationFromXml('<cfdi:Comprobante Version="3.3" xmlns:cfdi="http://www.sat.gob.mx/cfd/3"/>')
        );
    }

    public function testGetXsltLocationFromXmlThrowExceptionOnInvalidVersion()
    {
        $co = new CadenaOrigenLocations();
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Cannot get a xslt location from the document');
        $co->getXsltLocationFromXml('<cfdi:Comprobante Version="1.2" xmlns:cfdi="http://www.sat.gob.mx/cfd/3"/>');
    }

    public function testBuild()
    {
        $local = $this->downloadResourceIfNotExists(DefaultLocations::XSLT_32);
        $co = new CadenaOrigenLocations();
        $co->setXsltLocation('3.2', $local);

        $fileCfdi = $this->utilAsset('cfdi32-real.xml');
        $fileExpectedCadenaOrigen = $this->utilAsset('cfdi32-real-cadenaorigen.txt');

        $cadenaOrigen = $co->build(file_get_contents($fileCfdi));
        $this->assertStringEqualsFile($fileExpectedCadenaOrigen, $cadenaOrigen . "\n");
    }
}
