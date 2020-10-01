<?php

namespace CfdiUtils\Internals;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Nodes\XmlNodeUtils;
use CfdiUtils\QuickReader\QuickReader;
use CfdiUtils\QuickReader\QuickReaderImporter;
use CfdiUtils\Utils\Xml;
use DOMDocument;
use DOMElement;

/** @internal */
trait XmlReaderTrait
{
    /** @var DOMDocument */
    private $document;

    /** @var string */
    private $version;

    /** @var string|null */
    private $source;

    /** @var NodeInterface|null */
    private $node;

    /** @var QuickReader|null */
    private $quickReader;

    private static function checkRootElement(
        DOMDocument $document,
        string $expectedNamespace,
        string $expectedNsPrefix,
        string $expectedRootBaseNodeName
    ): DOMElement {
        $rootElement = Xml::documentElement($document);

        // is not docummented: lookupPrefix returns NULL instead of string when not found
        // this is why we are casting the value to string
        $nsPrefix = (string) $document->lookupPrefix($expectedNamespace);
        if ('' === $nsPrefix) {
            throw new \UnexpectedValueException(
                sprintf('Document does not implement namespace %s', $expectedNamespace)
            );
        }
        if ($expectedNsPrefix !== $nsPrefix) {
            throw new \UnexpectedValueException(
                sprintf('Prefix for namespace %s is not "%s"', $expectedNamespace, $expectedNsPrefix)
            );
        }

        $expectedRootNodeName = $expectedNsPrefix . ':' . $expectedRootBaseNodeName;
        if ($rootElement->tagName !== $expectedRootNodeName) {
            throw new \UnexpectedValueException(sprintf('Root element is not %s', $expectedRootNodeName));
        }

        return $rootElement;
    }

    /**
     * Create a CFDI object from a xml string
     *
     * @param string $content
     * @return self
     */
    public static function newFromString(string $content): self
    {
        $document = Xml::newDocumentContent($content);
        // populate source since it is already available, in this way we avoid the conversion from document to string
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $cfdi = new self($document);
        $cfdi->source = $content;
        return $cfdi;
    }

    /**
     * Obtain the version from the document, if the version was not detected returns an empty string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Get a clone of the local DOM Document
     */
    public function getDocument(): DOMDocument
    {
        return clone $this->document;
    }

    /**
     * Get the XML string source
     */
    public function getSource(): string
    {
        if (null === $this->source) {
            // pass the document element to avoid xml header
            $this->source = (string) $this->document->saveXML(Xml::documentElement($this->document));
        }

        return $this->source;
    }

    /**
     * Get the node object to iterate through the document
     */
    public function getNode(): NodeInterface
    {
        if (null === $this->node) {
            $this->node = XmlNodeUtils::nodeFromXmlElement(Xml::documentElement($this->document));
        }

        return $this->node;
    }

    /**
     * Get the quick reader object to iterate through the document
     */
    public function getQuickReader(): QuickReader
    {
        if (null === $this->quickReader) {
            $this->quickReader = (new QuickReaderImporter())->importDocument($this->document);
        }

        return $this->quickReader;
    }
}
