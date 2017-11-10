<?php
namespace CfdiUtilsTests\CadenaOrigen;

use CfdiUtils\CadenaOrigen\CadenaOrigenBuilder;
use CfdiUtils\CadenaOrigen\DefaultLocations;
use CfdiUtilsTests\TestCase;

class CadenaOrigenBuilderTest extends TestCase
{
    /**
     * The file cfdi33-valid-cadenaorigen.txt was created using the command line util saxonb-xslt
     * available in debian in the package libsaxonb-java.
     * To recreate these files use the method procedureCreateCadenaOrigenExpectedContent
     *
     * @see procedureCreateCadenaOrigenExpectedContent
     * @return array
     */
    public function providerCfdiToCadenaOrigen()
    {
        return [
            ['cfdi32-real.xml', 'cfdi32-real-cadenaorigen.txt', DefaultLocations::XSLT_32],
            ['cfdi33-valid.xml', 'cfdi33-valid-cadenaorigen.txt', DefaultLocations::XSLT_33],
        ];
    }

    /**
     * @param $xmlLocation
     * @param $expectedCadenaOrigen
     * @param $xsltLocation
     * @dataProvider providerCfdiToCadenaOrigen
     */
    public function testCfdiToCadenaOrigen($xmlLocation, $expectedCadenaOrigen, $xsltLocation)
    {
        $xsltLocation = $this->downloadResourceIfNotExists($xsltLocation);

        $xmlLocation = $this->utilAsset($xmlLocation);
        $expectedCadenaOrigen = $this->utilAsset($expectedCadenaOrigen);
        $expected = rtrim(file_get_contents($expectedCadenaOrigen));

        $co = new CadenaOrigenBuilder();
        $cadenaOrigen = $co->build(file_get_contents($xmlLocation), $xsltLocation);
        $this->assertEquals($expected, $cadenaOrigen);
    }

    /**
     * Use this procedure to recreate the expected files using saxonb-xslt.
     * It will not download anything, it will use internet locations
     *
     * NOTE: This procedure will not run unless you add the "test" annotation.
     * @ test
     *
     * @param $xmlLocation
     * @param $expectedCadenaOrigen
     * @param $xsltLocation
     * @dataProvider providerCfdiToCadenaOrigen
     */
    public function procedureCreateCadenaOrigenExpectedContent($xmlLocation, $expectedCadenaOrigen, $xsltLocation)
    {
        $xmlLocation = $this->utilAsset($xmlLocation);
        $expectedCadenaOrigen = $this->utilAsset($expectedCadenaOrigen);
        // uncomment the following line if you want to use local resources instead of internet
        // $xsltLocation = $this->downloadResourceIfNotExists($xsltLocation);

        $saxonb = shell_exec('which saxonb-xslt');
        if ('' === $saxonb) {
            $this->markTestSkipped('There is no saxonb-xslt installed');
            return;
        }
        $command = implode(' ', [
            escapeshellcmd($saxonb),
            escapeshellarg('-s:' . $xmlLocation),
            escapeshellarg('-xsl:' . $xsltLocation),
            '2>/dev/null',
        ]);
        $cadenaOrigen = shell_exec($command);

        file_put_contents($expectedCadenaOrigen, $cadenaOrigen . PHP_EOL);
        $this->assertFileExists($expectedCadenaOrigen);
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
