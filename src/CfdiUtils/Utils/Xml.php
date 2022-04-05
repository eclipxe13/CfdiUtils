<?php

namespace CfdiUtils\Utils;

use DOMDocument;
use DOMElement;
use DOMNode;
use LibXMLError;

class Xml
{
    public static function documentElement(DOMDocument $document): DOMElement
    {
        if (! $document->documentElement instanceof DOMElement) {
            throw new \UnexpectedValueException('DOM Document does not have root element');
        }
        return $document->documentElement;
    }

    public static function ownerDocument(DOMNode $node): DOMDocument
    {
        // $node->ownerDocument is NULL if node is a DOMDocument
        if (null === $node->ownerDocument) {
            if ($node instanceof DOMDocument) {
                return $node;
            }
            /** @codeCoverageIgnore */
            throw new \LogicException('node->ownerDocument is null but node is not a DOMDocument');
        }
        return $node->ownerDocument;
    }

    /**
     * Creates a DOMDocument object version 1.0 encoding UTF-8
     * with output formatting and not preserving white spaces
     *
     * @return DOMDocument
     */
    public static function newDocument(): DOMDocument
    {
        $document = new DOMDocument('1.0', 'UTF-8');
        $document->formatOutput = true;
        $document->preserveWhiteSpace = false;
        return $document;
    }

    public static function newDocumentContent(string $content): DOMDocument
    {
        if ('' === $content) {
            throw new \UnexpectedValueException('Received xml string argument is empty');
        }
        $document = static::newDocument();
        // this error silenced call is intentional, no need to alter libxml_use_internal_errors
        if (false === @$document->loadXML($content)) {
            throw new \UnexpectedValueException(
                trim('Cannot create a DOM Document from xml string' . PHP_EOL . self::castLibXmlLastErrorAsString())
            );
        }
        return $document;
    }

    private static function castLibXmlLastErrorAsString(): string
    {
        $error = libxml_get_last_error();
        if (! $error instanceof LibXMLError) {
            return '';
        }
        $types = [
            LIBXML_ERR_NONE => 'None',
            LIBXML_ERR_WARNING => 'Warning',
            LIBXML_ERR_ERROR => 'Error',
            LIBXML_ERR_FATAL => 'Fatal',
        ];
        return sprintf(
            'XML %s [L: %d, C: %d]: %s',
            $types[$error->level] ?? 'Unknown',
            $error->line,
            $error->column,
            $error->message
        );
    }

    public static function isValidXmlName(string $name): bool
    {
        if ('' === $name) {
            return false;
        }
        $pattern = '/^[:_A-Za-z'
            . '\xC0-\xD6\xD8-\xF6\xF8-\x{2FF}\x{370}-\x{37D}\x{37F}-\x{1FFF}\x{200C}-\x{200D}\x{2070}-\x{218F}'
            . '\x{2C00}-\x{2FEF}\x{3001}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFFD}\x{10000}-\x{EFFFF}]{1}'
            . '[\-:_A-Za-z0-9'
            . '\xC0-\xD6\xD8-\xF6\xF8-\x{2FF}\x{370}-\x{37D}\x{37F}-\x{1FFF}\x{200C}-\x{200D}\x{2070}-\x{218F}'
            . '\x{2C00}-\x{2FEF}\x{3001}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFFD}\x{10000}-\x{EFFFF}'
            . '\xB7\x{0300}-\x{036F}\x{203F}-\x{2040}]*$/u';
        return (1 === preg_match($pattern, $name));
    }

    /**
     * This is an alias of DOMDocument::createElement that will replace ampersand '&' with '&amp;'
     * @see https://www.php.net/manual/en/domdocument.createelement.php
     *
     * @param DOMDocument $document
     * @param string $name
     * @param string $content
     * @return DOMElement
     */
    public static function createElement(DOMDocument $document, string $name, string $content = ''): DOMElement
    {
        return self::createDOMElement(
            function () use ($document, $name) {
                return $document->createElement($name);
            },
            sprintf('Cannot create element with name %s', $name),
            $content
        );
    }

    /**
     * This is an alias of DOMDocument::createElementNS that will replace ampersand '&' with '&amp;'
     * @see https://www.php.net/manual/en/domdocument.createelementns.php
     *
     * @param DOMDocument $document
     * @param string $namespaceURI
     * @param string $name
     * @param string $content
     * @return DOMElement
     */
    public static function createElementNS(
        DOMDocument $document,
        string $namespaceURI,
        string $name,
        string $content = ''
    ): DOMElement {
        return self::createDOMElement(
            function () use ($document, $namespaceURI, $name) {
                return $document->createElementNS($namespaceURI, $name);
            },
            sprintf('Cannot create element with name %s namespace %s', $name, $namespaceURI),
            $content
        );
    }

    private static function createDOMElement(\Closure $fnCreate, string $errorMessage, string $content): DOMElement
    {
        /** @var DOMElement|null $element */
        $element = null;
        $previousException = null;
        try {
            $element = $fnCreate();
        } catch (\Throwable $creationException) {
            $previousException = $creationException;
        }
        if (! $element instanceof DOMElement) {
            throw new \LogicException($errorMessage, 0, $previousException);
        }
        if ('' !== $content) {
            $element->appendChild(static::ownerDocument($element)->createTextNode($content));
        }
        return $element;
    }
}
