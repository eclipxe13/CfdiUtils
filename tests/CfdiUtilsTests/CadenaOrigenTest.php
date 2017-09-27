<?php
namespace CfdiUtilsTests;

use CfdiUtils\CadenaOrigen;
use XmlResourceRetriever\XsltRetriever;

class CadenaOrigenTest extends TestCase
{
    public function testGetXsltLocationDefault()
    {
        $co = new CadenaOrigen();
        $this->assertEmpty($co->getXsltLocation('1.0'));
        $this->assertContains('cadenaoriginal_3_2/cadenaoriginal_3_2.xslt', $co->getXsltLocation('3.2'));
        $this->assertContains('cadenaoriginal_3_3/cadenaoriginal_3_3.xslt', $co->getXsltLocation('3.3'));
    }

    public function testGetXsltLocationsDefault()
    {
        $co = new CadenaOrigen();
        $locations = $co->getXsltLocations();
        $this->assertArrayHasKey('3.2', $locations);
        $this->assertArrayHasKey('3.3', $locations);
        $this->assertCount(2, $locations);
    }

    public function testSetXsltLocationsDefault()
    {
        $co = new CadenaOrigen();
        $changedLocation = '/foo/bar';
        $co->setXsltLocation('3.2', $changedLocation);
        $this->assertSame($changedLocation, $co->getXsltLocation('3.2'));
        $co->setXsltLocation('1.2', $changedLocation);
        $this->assertSame($changedLocation, $co->getXsltLocation('1.2'));
        $this->assertCount(3, $co->getXsltLocations());
    }

    /**
     * This function is used to ensure that local resources exists
     * @param string $remote
     * @return string
     */
    private function downloadResourcesIfNeeded(string $remote)
    {
        $retriever = new XsltRetriever($this->utilAsset(''));
        $local = $retriever->buildPath($remote);
        if (! file_exists($local)) {
            $retriever->retrieve($remote);
        }
        return $local;
    }

    public function testBuildWithLocalResourceAsXsltLocation()
    {
        $local = $this->downloadResourcesIfNeeded(
            'http://www.sat.gob.mx/sitio_internet/cfd/3/cadenaoriginal_3_2/cadenaoriginal_3_2.xslt'
        );

        $co = new CadenaOrigen();
        // change default location
        $co->setXsltLocation('3.2', $local);

        $fileCfdi = $this->utilAsset('cfdi32-real.xml');
        $fileExpectedCadenaOrigen = $this->utilAsset('cfdi32-real-cadenaorigen.txt');

        $cadenaOrigen = $co->build(file_get_contents($fileCfdi));
        $this->assertStringEqualsFile($fileExpectedCadenaOrigen, $cadenaOrigen . "\n");
    }

    public function testBuildWithLocalResourceAsArgument()
    {
        $local = $this->downloadResourcesIfNeeded(
            'http://www.sat.gob.mx/sitio_internet/cfd/3/cadenaoriginal_3_2/cadenaoriginal_3_2.xslt'
        );

        $co = new CadenaOrigen();

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

        $co = new CadenaOrigen();
        $cadenaOrigen = $co->build(file_get_contents($fileCfdi));
        $this->assertStringEqualsFile($fileExpectedCadenaOrigen, $cadenaOrigen . "\n");
    }

    public function testBuildWithEmptyXml()
    {
        $co = new CadenaOrigen();

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('empty');
        $co->build('');
    }

    public function testBuildWithInvalidXml()
    {
        $co = new CadenaOrigen();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Error while loading the cfdi content');
        $co->build('not an xml');
    }

    public function testBuildWithInvalidCfdiVersion()
    {
        $co = new CadenaOrigen();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Cannot get the CFDI version from the document');
        $co->build('<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" version="1.2"' . '/>');
    }

    public function testBuildWithUndefinedXsltLocation()
    {
        $co = new CadenaOrigen();
        $co->setXsltLocation('3.2', '');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Cannot get the CFDI version from the document');
        $co->build('<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" version="3.2"' . '/>');
    }

    public function testBuildWithInvalidXsltLocation()
    {
        $co = new CadenaOrigen();
        $co->setXsltLocation('3.2', '/foo/bar');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Error while loading the Xslt location');
        $co->build('<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" version="3.2"' . '/>');
    }

    public function testBuildWithNonXsltContent()
    {
        $co = new CadenaOrigen();
        $nonAnXsltFile = $this->utilAsset('simple-xml.xml');
        $co->setXsltLocation('3.2', $nonAnXsltFile);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Error while importing the style sheet');
        $co->build('<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" version="3.2"' . '/>');
    }

    public function testBuildWithEmptyXslt()
    {
        $co = new CadenaOrigen();
        $nonAnXsltFile = $this->utilAsset('empty.xslt');
        $co->setXsltLocation('3.2', $nonAnXsltFile);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Error while transforming the xslt content');
        $co->build('<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" version="3.2"' . '/>');
    }
}
