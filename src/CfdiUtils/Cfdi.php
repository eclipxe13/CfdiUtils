<?php

namespace CfdiUtils;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Nodes\XmlNodeUtils;
use CfdiUtils\QuickReader\QuickReader;
use CfdiUtils\QuickReader\QuickReaderImporter;
use CfdiUtils\Utils\Xml;
use DOMDocument;
use DOMElement;

/**
 * This class contains minimum helpers to read CFDI based on DOMDocument
 *
 * When the object is instantiated it checks that:
 * implements the namespace static::CFDI_NAMESPACE using a prefix
 * the root node is prefix + Comprobante
 *
 * This class also provides version information thru getVersion() method
 *
 * This class also provides conversion to Node for easy access and manipulation,
 * changes made in Node structure are not reflected into the DOMDocument,
 * changes made in DOMDocument three are not reflected into the Node,
 *
 * Use this class as your starting point to read documents
 */
class Cfdi
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

    const CFDI_NAMESPACE = 'http://www.sat.gob.mx/cfd/3';

    public function __construct(DOMDocument $document)
    {
        $rootElement = $this->extractValidRootElement($document, static::CFDI_NAMESPACE, 'cfdi', 'Comprobante');

        $this->version = (new CfdiVersion())->getFromDOMElement($rootElement);
        $this->document = clone $document;
    }


    private function extractValidRootElement(
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
     * @return static
     */
    public static function newFromString(string $content): self
    {
        $document = Xml::newDocumentContent($content);
        // populate source since it is already available
        // in this way we avoid the conversion from document to string
        $cfdi = new self($document);
        $cfdi->source = $content;
        return $cfdi;
    }

    /**
     * Obtain the version from the CFDI, it is compatible with 3.2 and 3.3
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Get a clone of the local DOM document
     */
    public function getDocument(): DOMDocument
    {
        return clone $this->document;
    }

    /**
     * Get the xml string source
     */
    public function getSource(): string
    {
        if (null === $this->source) {
            // pass the document element to avoid xml header
            $this->source = $this->document->saveXML(Xml::documentElement($this->document));
        }

        return $this->source;
    }

    /**
     * Get the node object to iterate through the CFDI
     */
    public function getNode(): NodeInterface
    {
        if (null === $this->node) {
            $this->node = XmlNodeUtils::nodeFromXmlElement(Xml::documentElement($this->document));
        }

        return $this->node;
    }

    /**
     * Get the quick reader object to iterate through the CFDI
     */
    public function getQuickReader(): QuickReader
    {
        if (null === $this->quickReader) {
            $this->quickReader = (new QuickReaderImporter())->importDocument($this->document);
        }

        return $this->quickReader;
    }
}
