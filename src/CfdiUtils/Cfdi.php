<?php

namespace CfdiUtils;

use CfdiUtils\Internals\XmlReaderTrait;
use DOMDocument;
use UnexpectedValueException;

/**
 * This class contains minimum helpers to read CFDI based on DOMDocument
 *
 * When the object is instantiated it checks that:
 * implements the namespace static::CFDI_NAMESPACE using a prefix
 * the root node is prefix + Comprobante
 *
 * This class also provides version information through getVersion() method
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

    /**
     * @var string CFDI 3 namespace definition
     * @deprecated :3.0.0
     * @internal Preserve this constant to not break compatibility
     */
    public const CFDI_NAMESPACE = 'http://www.sat.gob.mx/cfd/3';

    /** @var array<string, string> Dictionary of versions and namespaces  */
    private const CFDI_SPECS = [
        '4.0' => 'http://www.sat.gob.mx/cfd/4',
        '3.3' => 'http://www.sat.gob.mx/cfd/3',
        '3.2' => 'http://www.sat.gob.mx/cfd/3',
    ];

    public function __construct(DOMDocument $document)
    {
        $cfdiVersion = new CfdiVersion();
        /** @var array<string, UnexpectedValueException> $exceptions */
        $exceptions = [];
        foreach (self::CFDI_SPECS as $version => $namespace) {
            try {
                $this->loadDocumentWithNamespace($cfdiVersion, $document, $namespace);
                return;
            } catch (UnexpectedValueException $exception) {
                $exceptions[$version] = $exception;
            }
        }

        throw CfdiCreateObjectException::withVersionExceptions($exceptions);
    }

    /** @throws UnexpectedValueException */
    private function loadDocumentWithNamespace(CfdiVersion $cfdiVersion, DOMDocument $document, string $namespace): void
    {
        $rootElement = self::checkRootElement($document, $namespace, 'cfdi', 'Comprobante');
        $this->version = $cfdiVersion->getFromDOMElement($rootElement);
        $this->document = clone $document;
    }
}
