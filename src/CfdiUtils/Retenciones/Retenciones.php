<?php

namespace CfdiUtils\Retenciones;

use CfdiUtils\CfdiCreateObjectException;
use CfdiUtils\Internals\XmlReaderTrait;
use DOMDocument;
use UnexpectedValueException;

/**
 * This class contains minimum helpers to read CFDI Retenciones based on DOMDocument
 *
 * When the object is instantiated it checks that:
 * implements the namespace static::RET_NAMESPACE using a prefix "retenciones"
 * the root node is prefix:Retenciones
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

    /**
     * @var string Retenciones 1.0 namespace definition
     * @deprecated :3.0.0
     * @internal Preserve this constant to not break compatibility
     */
    const RET_NAMESPACE = 'http://www.sat.gob.mx/esquemas/retencionpago/1';

    /** @var array<string, string> Dictionary of versions and namespaces  */
    private const RET_SPECS = [
        '2.0' => 'http://www.sat.gob.mx/esquemas/retencionpago/2',
        '1.0' => 'http://www.sat.gob.mx/esquemas/retencionpago/1',
    ];

    public function __construct(DOMDocument $document)
    {
        $retVersion = new RetencionVersion();
        /** @var array<string, UnexpectedValueException> $exceptions */
        $exceptions = [];
        foreach (self::RET_SPECS as $version => $namespace) {
            try {
                $this->loadDocumentWithNamespace($retVersion, $document, $namespace);
                return;
            } catch (UnexpectedValueException $exception) {
                $exceptions[$version] = $exception;
            }
        }

        throw CfdiCreateObjectException::withVersionExceptions($exceptions);
    }

    /** @throws UnexpectedValueException */
    private function loadDocumentWithNamespace(
        RetencionVersion $retVersion,
        DOMDocument $document,
        string $namespace
    ): void {
        $rootElement = self::checkRootElement($document, $namespace, 'retenciones', 'Retenciones');
        $this->version = $retVersion->getFromDOMElement($rootElement);
        $this->document = clone $document;
    }
}
