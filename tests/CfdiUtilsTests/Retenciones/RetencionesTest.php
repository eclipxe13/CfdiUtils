<?php

namespace CfdiUtilsTests\Retenciones;

use CfdiUtils\CfdiCreateObjectException;
use CfdiUtils\Retenciones\Retenciones;
use CfdiUtils\Utils\Xml;
use CfdiUtilsTests\TestCase;

final class RetencionesTest extends TestCase
{
    public const XML_MINIMAL_DEFINITION = <<<XML
        <retenciones:Retenciones xmlns:retenciones="http://www.sat.gob.mx/esquemas/retencionpago/1" Version="1.0"/>
        XML;

    public const XML_20_MINIMAL_DEFINITION = <<<XML
        <retenciones:Retenciones xmlns:retenciones="http://www.sat.gob.mx/esquemas/retencionpago/2" Version="2.0"/>
        XML;

    public function providerRetencionesVersionNamespace(): array
    {
        return [
            '2.0' => ['2.0', 'http://www.sat.gob.mx/esquemas/retencionpago/2'],
            '1.0' => ['1.0', 'http://www.sat.gob.mx/esquemas/retencionpago/1'],
        ];
    }

    public function testNewFromStringWithEmptyXml()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('empty');
        Retenciones::newFromString('');
    }

    public function testNewFromStringWithInvalidXml()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Cannot create a DOM Document');
        Retenciones::newFromString(' ');
    }

    /** @dataProvider providerRetencionesVersionNamespace */
    public function testConstructWithoutNamespace(string $version, string $namespace)
    {
        $exception = $this->captureException(function () {
            Retenciones::newFromString('<Retenciones ' . '/>');
        });
        $this->assertInstanceOf(CfdiCreateObjectException::class, $exception);
        /** @var CfdiCreateObjectException $exception */
        $this->assertStringContainsString(
            'namespace ' . $namespace,
            $exception->getExceptionByVersion($version)->getMessage()
        );
    }

    /** @dataProvider providerRetencionesVersionNamespace */
    public function testConstructWithEmptyDomDocument(string $version)
    {
        $exception = $this->captureException(function () {
            new Retenciones(new \DOMDocument());
        });
        $this->assertInstanceOf(CfdiCreateObjectException::class, $exception);
        /** @var CfdiCreateObjectException $exception */
        $this->assertStringContainsString(
            'DOM Document does not have root element',
            $exception->getExceptionByVersion($version)->getMessage()
        );
    }

    /** @dataProvider providerRetencionesVersionNamespace */
    public function testInvalidCfdiRootIsNotComprobante(string $version, string $namespace)
    {
        $exception = $this->captureException(function () use ($namespace) {
            Retenciones::newFromString(sprintf('<retenciones:X xmlns:retenciones="%s"/>', $namespace));
        });
        $this->assertInstanceOf(CfdiCreateObjectException::class, $exception);
        /** @var CfdiCreateObjectException $exception */
        $this->assertStringContainsString(
            'Root element is not retenciones:Retenciones',
            $exception->getExceptionByVersion($version)->getMessage()
        );
    }

    /** @dataProvider providerRetencionesVersionNamespace */
    public function testInvalidCfdiRootIsPrefixedWithUnexpectedName(string $version, string $namespace)
    {
        $exception = $this->captureException(function () use ($namespace) {
            Retenciones::newFromString(sprintf('<x:Retenciones xmlns:x="%s"/>', $namespace));
        });
        $this->assertInstanceOf(CfdiCreateObjectException::class, $exception);
        /** @var CfdiCreateObjectException $exception */
        $this->assertStringContainsString(
            "Prefix for namespace $namespace is not \"retenciones\"",
            $exception->getExceptionByVersion($version)->getMessage()
        );
    }

    /** @dataProvider providerRetencionesVersionNamespace */
    public function testInvalidCfdiRootPrefixDoesNotMatchWithNamespaceDeclaration(string $version, string $namespace)
    {
        $exception = $this->captureException(function () use ($namespace) {
            Retenciones::newFromString(sprintf('<x:Retenciones xmlns:retenciones="%s"/>', $namespace));
        });
        $this->assertInstanceOf(CfdiCreateObjectException::class, $exception);
        /** @var CfdiCreateObjectException $exception */
        $this->assertStringContainsString(
            'Root element is not retenciones:Retenciones',
            $exception->getExceptionByVersion($version)->getMessage()
        );
    }

    public function testValid10()
    {
        $retencion = Retenciones::newFromString(self::XML_MINIMAL_DEFINITION);

        $this->assertEquals('1.0', $retencion->getVersion());
    }

    public function testValida20()
    {
        $retencion = Retenciones::newFromString(self::XML_20_MINIMAL_DEFINITION);

        $this->assertEquals('2.0', $retencion->getVersion());
    }

    public function testValid10WithXmlHeader()
    {
        $retencion = Retenciones::newFromString(
            '<?xml version="1.0" encoding="UTF-8" ?>' . PHP_EOL . self::XML_MINIMAL_DEFINITION
        );

        $this->assertEquals('1.0', $retencion->getVersion());
    }

    public function testVersion1980ReturnEmpty()
    {
        $retencion = Retenciones::newFromString(
            str_replace('Version="1.0"', 'Version="1980"', self::XML_MINIMAL_DEFINITION)
        );

        $this->assertEmpty($retencion->getVersion());
    }

    public function testVersionEmptyReturnEmpty()
    {
        $retencion = Retenciones::newFromString(
            str_replace('Version="1.0"', 'Version=""', self::XML_MINIMAL_DEFINITION)
        );

        $this->assertEmpty($retencion->getVersion());
    }

    public function testGetDocument()
    {
        $xml = self::XML_MINIMAL_DEFINITION;
        $document = Xml::newDocument();
        $document->loadXML($xml);
        $retencion = new Retenciones($document);

        $retrieved = $retencion->getDocument();

        $this->assertNotSame($document, $retrieved, 'The DOM Document must NOT be the same as constructed');
        $this->assertXmlStringEqualsXmlString($xml, $retrieved->saveXML(), 'The DOM Documents should be equal');
    }

    public function testGetSource()
    {
        $xml = self::XML_MINIMAL_DEFINITION;
        $retencion = Retenciones::newFromString($xml);

        $retrieved = $retencion->getSource();

        $this->assertSame($xml, $retrieved);
    }

    public function testGetNode()
    {
        $retencion = Retenciones::newFromString(self::XML_MINIMAL_DEFINITION);
        $node = $retencion->getNode();
        $this->assertSame($retencion->getVersion(), $node['Version']);
    }

    public function testGetQuickReader()
    {
        $retencion = Retenciones::newFromString(self::XML_MINIMAL_DEFINITION);
        $quickReader = $retencion->getQuickReader();
        $this->assertSame($retencion->getVersion(), $quickReader['VERSION']);
    }
}
