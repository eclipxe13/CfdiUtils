<?php

namespace CfdiUtils\Retenciones;

use CfdiUtils\Internals\XmlReaderTrait;
use DOMDocument;

/**
 * This class contains minimum helpers to read CFDI Retenciones based on DOMDocument
 *
 * When the object is instantiated it checks that:
 * implements the namespace static::RET_NAMESPACE using a prefix "retenciones"
 * the root node is retenciones:Retenciones
 *
 * This class also provides conversion to Node for easy access and manipulation,
 * changes made in Node structure are not reflected into the DOMDocument,
 * changes made in DOMDocument three are not reflected into the Node,
 *
 * Use this class as your starting point to read documents
 */
class Retenciones
{
    use XmlReaderTrait;

    const RET_NAMESPACE = 'http://www.sat.gob.mx/esquemas/retencionpago/1';

    public function __construct(DOMDocument $document)
    {
        $rootElement = self::checkRootElement($document, self::RET_NAMESPACE, 'retenciones', 'Retenciones');
        $this->document = clone $document;
        $version = $rootElement->getAttribute('Version');
        $this->version = ('1.0' === $version) ? $version : '';
    }
}
