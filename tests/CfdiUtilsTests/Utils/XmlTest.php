<?php

namespace CfdiUtilsTests\Utils;

use CfdiUtils\Utils\Xml;
use CfdiUtilsTests\TestCase;
use DOMDocument;

final class XmlTest extends TestCase
{
    public function testMethodNewDocumentContentWithInvalidXmlEncoding(): void
    {
        $invalidXml = mb_convert_encoding('<e a="ñ"></e>', 'ISO-8859-1', 'UTF-8'); // the ñ is a special character
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Cannot create a DOM Document from xml string'
            . PHP_EOL . 'XML Fatal [L: 1, C: 7]: Input is not proper UTF-8');
        Xml::newDocumentContent($invalidXml);
    }

    public function testMethodDocumentElementWithoutRootElement(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('DOM Document does not have root element');
        Xml::documentElement(new DOMDocument());
    }

    public function testMethodDocumentElementWithRootElement(): void
    {
        $document = new DOMDocument();
        $root = $document->createElement('root');
        $document->appendChild($root);
        $this->assertSame($root, Xml::documentElement($document));
    }

    /**
     * @param string $expected
     * @param string $content
     * @testWith ["", ""]
     *           ["foo", "foo"]
     *           ["&amp;", "&"]
     *           ["&lt;", "<"]
     *           ["&gt;", ">"]
     *           ["'", "'"]
     *           ["\"", "\""]
     *           ["&amp;copy;", "&copy;"]
     *           ["foo &amp; bar", "foo & bar"]
     */
    public function testMethodCreateElement(string $expected, string $content): void
    {
        $elementName = 'element';
        $document = Xml::newDocument();
        $element = Xml::createElement($document, $elementName, $content);
        $document->appendChild($element);
        $this->assertSame($content, $element->textContent);
        $this->assertXmlStringEqualsXmlString(
            sprintf('<%1$s>%2$s</%1$s>', $elementName, $expected),
            $document->saveXML($element)
        );
    }

    public function testMethodCreateElementWithBadName(): void
    {
        $document = Xml::newDocument();
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Cannot create element');
        Xml::createElement($document, '');
    }

    /**
     * @param string $expected
     * @param string $content
     * @testWith ["", ""]
     *           ["foo", "foo"]
     *           ["&amp;", "&"]
     *           ["&lt;", "<"]
     *           ["&gt;", ">"]
     *           ["'", "'"]
     *           ["\"", "\""]
     *           ["&amp;copy;", "&copy;"]
     *           ["foo &amp; bar", "foo & bar"]
     */
    public function testMethodCreateElementNs(string $expected, string $content): void
    {
        $prefix = 'foo';
        $namespaceURI = 'http://tempuri.org/';
        $elementName = $prefix . ':element';
        $document = Xml::newDocument();
        $element = Xml::createElementNS($document, $namespaceURI, $elementName, $content);
        $document->appendChild($element);
        $this->assertSame($content, $element->textContent);
        $this->assertXmlStringEqualsXmlString(
            sprintf('<%1$s xmlns:%3$s="%4$s">%2$s</%1$s>', $elementName, $expected, $prefix, $namespaceURI),
            $document->saveXML($element)
        );
    }

    public function testMethodCreateElementNsWithBadName(): void
    {
        $document = Xml::newDocument();
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Cannot create element');
        Xml::createElementNS($document, 'http://tempuri.org/', '');
    }

    public function testMethodCreateElementNsWithBadUri(): void
    {
        $document = Xml::newDocument();
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Cannot create element');
        Xml::createElementNS($document, '', 'x:foo');
    }
}
