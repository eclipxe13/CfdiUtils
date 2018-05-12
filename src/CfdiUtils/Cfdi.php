<?php
namespace CfdiUtils;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Nodes\XmlNodeImporter;
use CfdiUtils\Utils\Xml;
use DOMDocument;

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

    /** @var string */
    private $source;

    /** @var NodeInterface */
    private $node;

    const CFDI_NAMESPACE = 'http://www.sat.gob.mx/cfd/3';

    public function __construct(DOMDocument $document)
    {
        // is not docummented: lookupPrefix returns NULL instead of string when not found
        // this is why we are casting the value to string
        $nsPrefix = (string) $document->lookupPrefix(static::CFDI_NAMESPACE);
        if ('' === $nsPrefix) {
            throw new \UnexpectedValueException('Document does not implement namespace ' . static::CFDI_NAMESPACE);
        }
        if ('cfdi' !== $nsPrefix) {
            throw new \UnexpectedValueException('Prefix for namespace ' . static::CFDI_NAMESPACE . ' is not "cfdi"');
        }
        if ($document->documentElement->tagName !== $nsPrefix . ':Comprobante') {
            throw new \UnexpectedValueException('Root element is not Comprobante');
        }

        $this->version = CfdiVersion::fromDOMDocument($document);
        $this->document = clone $document;
    }

    /**
     * Create a Cfdi object from a xml string
     *
     * @param string $content
     *
     * @return static
     */
    public static function newFromString(string $content): self
    {
        $document = Xml::newDocumentContent($content);
        // populate source since it is already available
        // in this way we avoid the conversion from document to string
        $cfdi = new static($document);
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
     *
     * @return DOMDocument
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
            $this->source = $this->document->saveXML($this->document->documentElement);
        }
        return $this->source;
    }

    /**
     * Get the node object to iterate in the CFDI
     */
    public function getNode(): NodeInterface
    {
        if (null === $this->node) {
            $importer = new XmlNodeImporter();
            $this->node = $importer->import($this->document->documentElement);
        }
        return $this->node;
    }
}
