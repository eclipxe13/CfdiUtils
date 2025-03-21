<?php

namespace CfdiUtils\Cleaner;

use CfdiUtils\Cfdi;
use CfdiUtils\Cleaner\BeforeLoad\BeforeLoadCleanerInterface;
use CfdiUtils\Cleaner\Cleaners\SchemaLocationsXsdUrlsFixer;
use CfdiUtils\Utils\SchemaLocations;
use CfdiUtils\Utils\Xml;
use DOMAttr;
use DOMDocument;
use DOMNode;
use DOMNodeList;
use DOMXPath;
use LogicException;
use Throwable;

/**
 * Class to clean CFDI and avoid bad common practices.
 *
 * Strictly speaking, CFDI must accomplish all XML rules, including that any other
 * XML element must be isolated in its own namespace and follow their own XSD rules.
 *
 * The common practice (allowed by SAT) is that the CFDI is created, signed and
 * some nodes are attached after sign, some of them does not follow the XML standard.
 *
 * This is why it's better to clear Comprobante/Addenda and remove unused namespaces
 */
class Cleaner
{
    protected ?DOMDocument $dom = null;

    private BeforeLoadCleanerInterface $beforeLoadCleaner;

    public function __construct(string $content, ?BeforeLoadCleanerInterface $beforeLoadCleaner = null)
    {
        $this->beforeLoadCleaner = $beforeLoadCleaner ?? new BeforeLoad\BeforeLoadCleaner();
        if ('' !== $content) {
            $this->load($content);
        }
    }

    /**
     * Method to clean content and return the result
     * If an error occurs, an exception is thrown
     */
    public static function staticClean(string $content): string
    {
        $cleaner = new self($content);
        $cleaner->clean();
        return $cleaner->retrieveXml();
    }

    /**
     * Check if the CFDI version is compatible to this class
     */
    public static function isVersionAllowed(string $version): bool
    {
        return in_array($version, ['3.2', '3.3', '4.0']);
    }

    /**
     * Check if a given namespace is allowed (must not be removed from CFDI)
     */
    public static function isNameSpaceAllowed(string $namespace): bool
    {
        return (
            'http://www.w3.org/' === (substr($namespace, 0, 18) ?: '')
            || 'http://www.sat.gob.mx/' === (substr($namespace, 0, 22) ?: '')
        );
    }

    /**
     * Apply all removals (Addenda, Non SAT Nodes and Non SAT namespaces)
     */
    public function clean(): void
    {
        $this->removeAddenda();
        $this->removeIncompleteSchemaLocations();
        $this->removeNonSatNSNodes();
        $this->removeNonSatNSschemaLocations();
        $this->removeUnusedNamespaces();
        $this->collapseComprobanteComplemento();
        $this->fixKnownSchemaLocationsXsdUrls();
    }

    /**
     * Load the string content as a CFDI
     * This is exposed to reuse the current object instead of create a new instance
     *
     *
     * @throws CleanerException when the content is not valid xml
     * @throws CleanerException when the document does not use the namespace http://www.sat.gob.mx/cfd/3
     * @throws CleanerException when cannot find a Comprobante version (or Version) attribute
     * @throws CleanerException when the version is not compatible
     */
    public function load(string $content): void
    {
        try {
            $content = $this->beforeLoadCleaner->clean($content);
            $cfdi = Cfdi::newFromString($content);
        } catch (Throwable $exception) {
            throw new CleanerException($exception->getMessage(), $exception->getCode(), $exception->getPrevious());
        }
        $version = $cfdi->getVersion();
        if (! $this->isVersionAllowed($version)) {
            throw new CleanerException("The CFDI version '$version' is not allowed");
        }
        $this->dom = $cfdi->getDocument();
    }

    /**
     * Get the XML content of the CFDI
     */
    public function retrieveXml(): string
    {
        return $this->dom()->saveXML();
    }

    /**
     * Get a clone of the XML DOM Document of the CFDI
     */
    public function retrieveDocument(): DOMDocument
    {
        return clone $this->dom();
    }

    /**
     * Procedure to remove the Comprobante/Addenda node
     */
    public function removeAddenda(): void
    {
        $query = '/cfdi:Comprobante/cfdi:Addenda';
        $addendas = $this->xpathQuery($query);
        foreach ($addendas as $addenda) {
            $addenda->parentNode->removeChild($addenda);
        }
    }

    /**
     * Procedure to drop schemaLocations where second part does not end with '.xsd'
     */
    public function removeIncompleteSchemaLocations(): void
    {
        foreach ($this->obtainXsiSchemaLocations() as $attribute) {
            $attribute->nodeValue = $this->removeIncompleteSchemaLocation($attribute->nodeValue);
        }
    }

    private function removeIncompleteSchemaLocation(string $source): string
    {
        $schemaLocations = SchemaLocations::fromStingStrictXsd($source);
        foreach ($schemaLocations->getNamespacesWithoutLocation() as $namespace) {
            $schemaLocations->remove($namespace);
        }
        return $schemaLocations->asString();
    }

    /**
     * Procedure to drop schemaLocations that are not allowed
     * If the schemaLocation is empty then remove the attribute
     */
    public function removeNonSatNSschemaLocations(): void
    {
        $schemaLocations = $this->obtainXsiSchemaLocations();
        foreach ($schemaLocations as $attribute) {
            $this->removeNonSatNSschemaLocation($attribute);
        }
    }

    private function removeNonSatNSschemaLocation(DOMAttr $schemaLocation): void
    {
        $source = $schemaLocation->nodeValue;
        // load locations
        $schemaLocations = SchemaLocations::fromString($source, true);
        if ($schemaLocations->hasAnyNamespaceWithoutLocation()) {
            throw new CleanerException(
                sprintf("The schemaLocation value '%s' must have even number of URIs", $source)
            );
        }
        // filter
        foreach ($schemaLocations as $namespace => $location) {
            if (! $this->isNameSpaceAllowed($namespace)) {
                $schemaLocations->remove($namespace);
            }
        }
        // apply
        $modified = $schemaLocations->asString();
        if ($schemaLocations->isEmpty()) { // remove node
            $schemaLocation->ownerElement->removeAttributeNode($schemaLocation);
        } elseif ($source !== $modified) { // replace node content and is different
            $schemaLocation->nodeValue = $modified;
        }
    }

    /**
     * Procedure to remove all nodes that are not from an allowed namespace
     */
    public function removeNonSatNSNodes(): void
    {
        $nss = $this->obtainNamespaces();
        foreach ($nss as $namespace) {
            if (! $this->isNameSpaceAllowed($namespace)) {
                $this->removeNonSatNSNode($namespace);
            }
        }
    }

    /**
     * Procedure to remove all nodes from a specific namespace
     */
    private function removeNonSatNSNode(string $namespace): void
    {
        foreach ($this->dom()->getElementsByTagNameNS($namespace, '*') as $children) {
            $children->parentNode->removeChild($children);
        }
    }

    /**
     * Procedure to remove not allowed xmlns definitions
     */
    public function removeUnusedNamespaces(): void
    {
        $nss = [];
        $dom = $this->dom();
        $namespaces = $this->obtainNamespaces();
        foreach ($namespaces as $namespace) {
            if (! $namespace || $this->isNameSpaceAllowed($namespace)) {
                continue;
            }
            $prefix = $dom->lookupPrefix($namespace);
            $nss[$prefix] = $namespace;
        }
        $documentElement = Xml::documentElement($dom);
        foreach ($nss as $prefix => $namespace) {
            $documentElement->removeAttributeNS($namespace, $prefix);
        }
    }

    /**
     * Procedure to collapse Complemento elements from Comprobante
     * Collapse will take its children and put then on the first Complemento found
     */
    public function collapseComprobanteComplemento(): void
    {
        $comprobante = Xml::documentElement($this->dom());
        $complementos = $this->xpathQuery('./cfdi:Complemento', $comprobante);
        if ($complementos->length < 2) {
            return; // nothing to do, there are less than 2 complemento
        }
        $first = null;
        /** @var DOMNode $extra */
        foreach ($complementos as $extra) { // iterate over all extra children
            if (null === $first) {
                $first = $extra;
                continue;
            }
            $comprobante->removeChild($extra); // remove extra child from parent
            while ($extra->childNodes->length > 0) { // append extra child contents into first child
                /** @var DOMNode $child */
                $child = $extra->childNodes->item(0);
                $extra->removeChild($child);
                $first->appendChild($child);
            }
        }
    }

    /**
     * Procedure to fix XSD known location paths for CFDI and TFD
     */
    public function fixKnownSchemaLocationsXsdUrls(): void
    {
        $xsiLocations = $this->obtainXsiSchemaLocations();
        $schemasFixer = SchemaLocationsXsdUrlsFixer::createWithKnownSatUrls();
        foreach ($xsiLocations as $attribute) {
            $schemasFixer->fixSchemaLocationAttribute($attribute);
        }
    }

    /** @return DOMNodeList<DOMAttr> */
    private function obtainXsiSchemaLocations(): DOMNodeList
    {
        // Do not assume that prefix for http://www.w3.org/2001/XMLSchema-instance is "xsi"
        $xsi = $this->dom()->lookupPrefix('http://www.w3.org/2001/XMLSchema-instance');
        if (! $xsi) {
            return new DOMNodeList();
        }
        return $this->xpathQuery("//@$xsi:schemaLocation");
    }

    /** @return string[] */
    private function obtainNamespaces(): array
    {
        return array_unique(array_column(iterator_to_array($this->xpathQuery('//namespace::*')), 'nodeValue'));
    }

    /**
     * Helper function to perform a XPath query using an element (or root element)
     */
    private function xpathQuery(string $query, ?DOMNode $element = null): DOMNodeList
    {
        if (null === $element) {
            $document = $this->dom();
            $element = Xml::documentElement($document);
        } else {
            $document = Xml::ownerDocument($element);
        }
        $nodelist = (new DOMXPath($document))->query($query, $element);
        if (false === $nodelist) {
            $nodelist = new DOMNodeList();
        }
        return $nodelist;
    }

    private function dom(): DOMDocument
    {
        if (null === $this->dom) {
            throw new LogicException('No document has been loaded');
        }
        return $this->dom;
    }
}
