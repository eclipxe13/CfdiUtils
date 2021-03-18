<?php

namespace CfdiUtilsTests\Retenciones;

use CfdiUtils\Retenciones\Retenciones;
use CfdiUtils\Utils\Xml;
use CfdiUtilsTests\TestCase;

final class RetencionesTest extends TestCase
{
    const XML_MINIMAL_DEFINITION = <<<XML
<retenciones:Retenciones xmlns:retenciones="http://www.sat.gob.mx/esquemas/retencionpago/1" Version="1.0"/>
XML;

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

    public function testConstructWithoutNamespace()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('namespace http://www.sat.gob.mx/esquemas/retencionpago/1');
        Retenciones::newFromString('<Retenciones version="1.0"' . '/>');
    }

    public function testConstructWithEmptyDomDocument()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('DOM Document does not have root element');
        new Retenciones(new \DOMDocument());
    }

    public function testInvalidCfdiRootIsNotComprobante()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Root element is not retenciones:Retenciones');

        Retenciones::newFromString(
            str_replace('<retenciones:Retenciones', '<retenciones:foo', self::XML_MINIMAL_DEFINITION)
        );
    }

    public function testInvalidCfdiRootIsPrefixedWithUnexpectedName()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage(
            'Prefix for namespace http://www.sat.gob.mx/esquemas/retencionpago/1 is not "retenciones"'
        );

        Retenciones::newFromString(
            str_replace(
                ['<retenciones:', 'xmlns:retenciones'],
                ['<foo:', 'xmlns:foo'],
                self::XML_MINIMAL_DEFINITION
            )
        );
    }

    public function testInvalidCfdiRootPrefixDoesNotMatchWithNamespaceDeclaration()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Root element is not retenciones:Retenciones');

        Retenciones::newFromString(
            str_replace('<retenciones:Retenciones', '<foo:Retenciones', self::XML_MINIMAL_DEFINITION)
        );
    }

    public function testValid10()
    {
        $retencion = Retenciones::newFromString(self::XML_MINIMAL_DEFINITION);

        $this->assertEquals('1.0', $retencion->getVersion());
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
        $this->assertXmlStringEqualsXmlString($document, $retrieved, 'The DOM Documents should be equal');
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
