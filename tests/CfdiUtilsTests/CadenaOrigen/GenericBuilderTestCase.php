<?php

namespace CfdiUtilsTests\CadenaOrigen;

use CfdiUtils\CadenaOrigen\CfdiDefaultLocations;
use CfdiUtils\CadenaOrigen\XsltBuilderInterface;
use CfdiUtils\CadenaOrigen\XsltBuildException;
use CfdiUtilsTests\TestCase;

abstract class GenericBuilderTestCase extends TestCase
{
    abstract protected function createBuilder(): XsltBuilderInterface;

    /**
     * The file cfdi33-valid-cadenaorigen.txt was created using the command line util saxonb-xslt
     * available in debian in the package libsaxonb-java.
     * To recreate these files use the method procedureCreateCadenaOrigenExpectedContent
     *
     * @see procedureCreateCadenaOrigenExpectedContent
     * @return array
     */
    public function providerCfdiToCadenaOrigen(): array
    {
        return [
            ['cfdi32-real.xml', 'cfdi32-real-cadenaorigen.txt', CfdiDefaultLocations::XSLT_32],
            ['cfdi33-valid.xml', 'cfdi33-valid-cadenaorigen.txt', CfdiDefaultLocations::XSLT_33],
        ];
    }

    /**
     * @param string $xmlLocation
     * @param string $expectedTransformation
     * @param string $xsltLocation
     * @dataProvider providerCfdiToCadenaOrigen
     */
    public function testCfdiToCadenaOrigen(string $xmlLocation, string $expectedTransformation, string $xsltLocation)
    {
        $xsltLocation = $this->downloadResourceIfNotExists($xsltLocation);

        $xmlLocation = $this->utilAsset($xmlLocation);
        $expectedTransformation = $this->utilAsset($expectedTransformation);

        $builder = $this->createBuilder();
        $cadenaOrigen = $builder->build(strval(file_get_contents($xmlLocation)), $xsltLocation);
        $this->assertSame(
            rtrim(strval(file_get_contents($expectedTransformation))),
            $cadenaOrigen,
            'Xslt transformation returns an unexpected value'
        );
    }

    public function testBuildWithEmptyXml()
    {
        $builder = $this->createBuilder();

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('empty');
        $builder->build('', '');
    }

    public function testBuildWithInvalidXml()
    {
        $builder = $this->createBuilder();

        $this->expectException(XsltBuildException::class);
        $builder->build('not an xml', 'x');
    }

    public function testBuildWithUndefinedXsltLocation()
    {
        $builder = $this->createBuilder();

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Xslt location was not set');
        $builder->build('<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" version="3.2"' . '/>', '');
    }

    public function testBuildWithInvalidXsltLocation()
    {
        $builder = $this->createBuilder();

        $this->expectException(XsltBuildException::class);
        $builder->build('<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" version="3.2"' . '/>', '/foo/bar');
    }

    public function testBuildWithNonXsltContent()
    {
        $builder = $this->createBuilder();
        $nonAnXsltFile = $this->utilAsset('simple-xml.xml');

        $this->expectException(XsltBuildException::class);
        $builder->build(
            '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" version="3.2"' . '/>',
            $nonAnXsltFile
        );
    }

    public function testBuildWithEmptyXslt()
    {
        $builder = $this->createBuilder();
        $emptyXsltFile = $this->utilAsset('empty.xslt');

        $this->expectException(XsltBuildException::class);
        $builder->build(
            '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" version="3.2"' . '/>',
            $emptyXsltFile
        );
    }

    /**
     * This test require internet connection, not really required, run only if found errors
     * on method build using xslt files from internet
     */
    public function skippedTestBuildWithRemoteResource()
    {
        $fileCfdi = $this->utilAsset('cfdi32-real.xml');
        $fileExpectedCadenaOrigen = $this->utilAsset('cfdi32-real-cadenaorigen.txt');

        $builder = $this->createBuilder();
        $cadenaOrigen = $builder->build(strval(file_get_contents($fileCfdi)), CfdiDefaultLocations::XSLT_32);
        $this->assertStringEqualsFile($fileExpectedCadenaOrigen, $cadenaOrigen . PHP_EOL);
    }
}
