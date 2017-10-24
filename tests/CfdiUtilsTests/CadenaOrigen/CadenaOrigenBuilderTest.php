<?php
namespace CfdiUtilsTests\CadenaOrigen;

use CfdiUtils\CadenaOrigen\CadenaOrigenBuilder;
use CfdiUtils\CadenaOrigen\DefaultLocations;
use CfdiUtilsTests\TestCase;

class CadenaOrigenBuilderTest extends TestCase
{
    public function testBuildWithLocalResource()
    {
        $local = $this->downloadResourceIfNotExists(DefaultLocations::XSLT_32);

        $co = new CadenaOrigenBuilder();

        $fileCfdi = $this->utilAsset('cfdi32-real.xml');
        $fileExpectedCadenaOrigen = $this->utilAsset('cfdi32-real-cadenaorigen.txt');

        $cadenaOrigen = $co->build(file_get_contents($fileCfdi), $local);
        $this->assertStringEqualsFile($fileExpectedCadenaOrigen, $cadenaOrigen . "\n");
    }

    /**
     * This test require internet connection, not really required, run only if found errors
     * on method build using xslt files from internet
     */
    public function skippedtestBuildWithRemoteResource()
    {
        $fileCfdi = $this->utilAsset('cfdi32-real.xml');
        $fileExpectedCadenaOrigen = $this->utilAsset('cfdi32-real-cadenaorigen.txt');

        $co = new CadenaOrigenBuilder();
        $cadenaOrigen = $co->build(file_get_contents($fileCfdi), DefaultLocations::XSLT_32);
        $this->assertStringEqualsFile($fileExpectedCadenaOrigen, $cadenaOrigen . "\n");
    }

    public function testBuildWithEmptyXml()
    {
        $co = new CadenaOrigenBuilder();

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('empty');
        $co->build('', '');
    }

    public function testBuildWithInvalidXml()
    {
        $co = new CadenaOrigenBuilder();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Error while loading the cfdi content');
        $co->build('not an xml', 'x');
    }

    public function testBuildWithUndefinedXsltLocation()
    {
        $co = new CadenaOrigenBuilder();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Xslt location was not set');
        $co->build('<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" version="3.2"' . '/>', '');
    }

    public function testBuildWithInvalidXsltLocation()
    {
        $co = new CadenaOrigenBuilder();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Error while loading the Xslt location');
        $co->build('<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" version="3.2"' . '/>', '/foo/bar');
    }

    public function testBuildWithNonXsltContent()
    {
        $co = new CadenaOrigenBuilder();
        $nonAnXsltFile = $this->utilAsset('simple-xml.xml');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Error while importing the style sheet');
        $co->build('<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" version="3.2"' . '/>', $nonAnXsltFile);
    }

    public function testBuildWithEmptyXslt()
    {
        $co = new CadenaOrigenBuilder();
        $nonAnXsltFile = $this->utilAsset('empty.xslt');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Error while transforming the xslt content');
        $co->build('<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" version="3.2"' . '/>', $nonAnXsltFile);
    }
}
