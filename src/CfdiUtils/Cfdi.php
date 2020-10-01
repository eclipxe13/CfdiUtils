<?php

namespace CfdiUtils;

use CfdiUtils\Internals\XmlReaderTrait;
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
    use XmlReaderTrait;

    const CFDI_NAMESPACE = 'http://www.sat.gob.mx/cfd/3';

    public function __construct(DOMDocument $document)
    {
        $rootElement = self::checkRootElement($document, static::CFDI_NAMESPACE, 'cfdi', 'Comprobante');
        $this->document = clone $document;
        $this->version = (new CfdiVersion())->getFromDOMElement($rootElement);
    }
}
