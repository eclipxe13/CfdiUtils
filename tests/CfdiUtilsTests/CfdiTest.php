<?php

namespace CfdiUtilsTests;

use CfdiUtils\Cfdi;
use CfdiUtils\CfdiCreateObjectException;

final class CfdiTest extends TestCase
{
    public function providerCfdiVersionNamespace(): array
    {
        return [
            '4.0' => ['4.0', 'http://www.sat.gob.mx/cfd/4'],
            '3.3' => ['3.3', 'http://www.sat.gob.mx/cfd/3'],
            '3.2' => ['3.2', 'http://www.sat.gob.mx/cfd/3'],
        ];
    }

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

    /** @dataProvider providerCfdiVersionNamespace */
    public function testConstructWithoutNamespace(string $version, string $namespace)
    {
        $exception = $this->captureException(function () {
            Cfdi::newFromString('<Comprobante ' . '/>');
        });
        $this->assertInstanceOf(CfdiCreateObjectException::class, $exception);
        /** @var CfdiCreateObjectException $exception */
        $this->assertStringContainsString(
            'namespace ' . $namespace,
            $exception->getExceptionByVersion($version)->getMessage()
        );
    }

    /** @dataProvider providerCfdiVersionNamespace */
    public function testConstructWithEmptyDomDocument(string $version)
    {
        $exception = $this->captureException(function () {
            new Cfdi(new \DOMDocument());
        });
        $this->assertInstanceOf(CfdiCreateObjectException::class, $exception);
        /** @var CfdiCreateObjectException $exception */
        $this->assertStringContainsString(
            'DOM Document does not have root element',
            $exception->getExceptionByVersion($version)->getMessage()
        );
    }

    /** @dataProvider providerCfdiVersionNamespace */
    public function testInvalidCfdiRootIsNotComprobante(string $version, string $namespace)
    {
        $exception = $this->captureException(function () use ($namespace) {
            Cfdi::newFromString(sprintf('<cfdi:X xmlns:cfdi="%s"/>', $namespace));
        });
        $this->assertInstanceOf(CfdiCreateObjectException::class, $exception);
        /** @var CfdiCreateObjectException $exception */
        $this->assertStringContainsString(
            'Root element is not cfdi:Comprobante',
            $exception->getExceptionByVersion($version)->getMessage()
        );
    }

    /** @dataProvider providerCfdiVersionNamespace */
    public function testInvalidCfdiRootIsNotPrefixed(string $version, string $namespace)
    {
        $exception = $this->captureException(function () use ($namespace) {
            Cfdi::newFromString(sprintf('<x:Comprobante xmlns:cfdi="%s"/>', $namespace));
        });
        $this->assertInstanceOf(CfdiCreateObjectException::class, $exception);
        /** @var CfdiCreateObjectException $exception */
        $this->assertStringContainsString(
            'Root element is not cfdi:Comprobante',
            $exception->getExceptionByVersion($version)->getMessage()
        );
    }

    /** @dataProvider providerCfdiVersionNamespace */
    public function testInvalidCfdiRootIncorrectPrefix(string $version, string $namespace)
    {
        $exception = $this->captureException(function () use ($namespace) {
            Cfdi::newFromString(sprintf('<x:Comprobante xmlns:x="%s"/>', $namespace));
        });
        $this->assertInstanceOf(CfdiCreateObjectException::class, $exception);
        /** @var CfdiCreateObjectException $exception */
        $this->assertStringContainsString(
            "Prefix for namespace $namespace is not \"cfdi\"",
            $exception->getExceptionByVersion($version)->getMessage()
        );
    }

    public function testValid32()
    {
        $cfdi = Cfdi::newFromString(
            '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" version="3.2"' . '/>'
        );

        $this->assertEquals('3.2', $cfdi->getVersion());
    }

    public function testValid33()
    {
        $cfdi = Cfdi::newFromString(
            '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" Version="3.3"' . '/>'
        );

        $this->assertEquals('3.3', $cfdi->getVersion());
    }

    public function testValid40()
    {
        $cfdi = Cfdi::newFromString(
            '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/4" Version="4.0"' . '/>'
        );

        $this->assertEquals('4.0', $cfdi->getVersion());
    }

    public function testValid33WithXmlHeader()
    {
        $cfdi = Cfdi::newFromString(
            '<?xml version="1.0" encoding="UTF-8" ?>'
            . '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" Version="3.3"' . '/>'
        );

        $this->assertEquals('3.3', $cfdi->getVersion());
    }

    public function testVersion1980ReturnEmpty()
    {
        $cfdi = Cfdi::newFromString(
            '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" Version="1.9.80"' . '/>'
        );

        $this->assertEmpty($cfdi->getVersion());
    }

    public function testVersionEmptyReturnEmpty()
    {
        $cfdi = Cfdi::newFromString(
            '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" Version=""' . '/>'
        );

        $this->assertEmpty($cfdi->getVersion());
    }
}
