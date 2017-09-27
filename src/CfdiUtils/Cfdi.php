<?php
namespace CfdiUtils;

use DOMDocument;
use DOMXPath;

/**
 * This class contains minimum helpers to read CFDI based on DOMDocument
 *
 * When the object is instantiated it checks that:
 * implements the namespace static::CFDI_NAMESPACE using a prefix
 * the root node is prefix + Comprobante
 *
 * This class also provides version information
 *
 * It is used by CfdiVersion and CfdiCertificado
 */
class Cfdi
{
    /** @var DOMDocument */
    private $document;

    /** @var string */
    private $nsPrefix;

    /** @var string */
    private $version;

    const CFDI_NAMESPACE = 'http://www.sat.gob.mx/cfd/3';

    public function __construct(DOMDocument $document)
    {
        // is not docummented: lookupPrefix returns NULL instead of string when not found
        // this is why we are casting the value to string
        $nsPrefix = (string) $document->lookupPrefix(static::CFDI_NAMESPACE);
        if ('' === $nsPrefix) {
            throw new \UnexpectedValueException('Document does not implement namespace ' . static::CFDI_NAMESPACE);
        }

        if ($document->documentElement->tagName !== $nsPrefix . ':Comprobante') {
            throw new \UnexpectedValueException('Root element is not Comprobante');
        }

        $this->document = $document;
        $this->nsPrefix = $nsPrefix;
        $this->version = $this->obtainVersion();
    }

    /**
     * @param string $content
     *
     * @return static
     */
    public static function newFromString(string $content)
    {
        if ('' === $content) {
            throw new \UnexpectedValueException('Content is empty');
        }
        $document = new DOMDocument();
        // this error silenced call is intentional, no need to alter libxml_use_internal_errors
        if (false === @$document->loadXML($content)) {
            throw new \UnexpectedValueException('Cannot create a DOM Document from content');
        }
        return new static($document);
    }

    protected function obtainVersion(): string
    {
        if ('3.2' === $this->queryComprobanteAttribute('version')) {
            return '3.2';
        }
        if ('3.3' === $this->queryComprobanteAttribute('Version')) {
            return '3.3';
        }
        return '';
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param string $attribute
     * @return string
     */
    protected function queryComprobanteAttribute(string $attribute): string
    {
        $docElement = $this->document->documentElement;
        $query = '/' . $this->nsPrefix . ':Comprobante/@' . $attribute;
        $nodes = (new DOMXPath($docElement->ownerDocument))->query($query, $docElement);
        if ($nodes->length === 1) {
            return (string) $nodes->item(0)->nodeValue;
        }
        return '';
    }
}
