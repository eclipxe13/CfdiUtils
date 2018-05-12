<?php
namespace CfdiUtils\Cleaner;

use CfdiUtils\Cfdi;
use DOMDocument;
use DOMNode;
use DOMNodeList;
use DOMXPath;

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
    /** @var DOMDocument|null */
    protected $dom;

    public function __construct(string $content)
    {
        if ('' !== $content) {
            $this->load($content);
        }
    }

    /**
     * Method to clean content and return the result
     * If an error occurs, an exception is thrown
     * @param string $content
     * @return string
     */
    public static function staticClean($content): string
    {
        $cleaner = new self($content);
        $cleaner->clean();
        return $cleaner->retrieveXml();
    }

    /**
     * Check if the CFDI version is complatible to this class
     * @param string $version
     * @return bool
     */
    public static function isVersionAllowed(string $version): bool
    {
        return in_array($version, ['3.2', '3.3']);
    }

    /**
     * Check if a given namespace is allowed (must not be removed from CFDI)
     * @param string $namespace
     * @return bool
     */
    public static function isNameSpaceAllowed(string $namespace): bool
    {
        $fixedNS = [
            'http://www.w3.org/2001/XMLSchema-instance',
            'http://www.w3.org/XML/1998/namespace',
        ];
        foreach ($fixedNS as $ns) {
            if (0 === strcasecmp($ns, $namespace)) {
                return true;
            }
        }
        $willcardNS = [
            'http://www.sat.gob.mx/',
        ];
        foreach ($willcardNS as $ns) {
            if (0 === strpos($namespace, $ns)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Apply all removals (Addenda, Non SAT Nodes and Non SAT namespaces)
     * @return void
     */
    public function clean()
    {
        $this->removeAddenda();
        $this->removeNonSatNSNodes();
        $this->removeNonSatNSschemaLocations();
        $this->removeUnusedNamespaces();
    }

    /**
     * Load the string content as a CFDI
     * This is exposed to reuse the current object instead of create a new instance
     *
     * @param string $content
     *
     * @throws CleanerException when the content is not valid xml
     * @throws CleanerException when the document does not use the namespace http://www.sat.gob.mx/cfd/3
     * @throws CleanerException when cannot find a Comprobante version (or Version) attribute
     * @throws CleanerException when the version is not compatible
     *
     * @return void
     */
    public function load(string $content)
    {
        try {
            $cfdi = Cfdi::newFromString($content);
        } catch (\Throwable $exception) {
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
     *
     * @return string
     */
    public function retrieveXml(): string
    {
        return $this->dom()->saveXML();
    }

    /**
     * Get a clone of the XML DOM Docoment of the CFDI
     *
     * @return DOMDocument
     */
    public function retrieveDocument(): DOMDocument
    {
        return clone $this->dom();
    }

    /**
     * Procedure to remove the Comprobante/Addenda node
     *
     * @return void
     */
    public function removeAddenda()
    {
        $query = '/cfdi:Comprobante/cfdi:Addenda';
        $addendas = $this->xpathQuery($query);
        if ($addendas->length == 0) {
            return;
        }
        for ($i = 0; $i < $addendas->length; $i++) {
            $addenda = $addendas->item($i);
            $addenda->parentNode->removeChild($addenda);
        }
    }

    /**
     * Procedure to drop schemaLocations that are not allowed
     * If the schemaLocation is empty then remove the attribute
     *
     * @return void
     */
    public function removeNonSatNSschemaLocations()
    {
        // is weird, but xsi namespace can be declared with other prefix
        $xsi = $this->dom()->lookupPrefix('http://www.w3.org/2001/XMLSchema-instance');
        if (! $xsi) {
            return;
        }
        $schemaLocations = $this->xpathQuery("//@$xsi:schemaLocation");
        if ($schemaLocations->length === 0) {
            return;
        }
        for ($s = 0; $s < $schemaLocations->length; $s++) {
            $this->removeNonSatNSschemaLocation($schemaLocations->item($s));
        }
    }

    /**
     * @param DOMNode $schemaLocation This is the attribute
     * @return void
     */
    private function removeNonSatNSschemaLocation(DOMNode $schemaLocation)
    {
        $source = $schemaLocation->nodeValue;
        $parts = array_values(array_filter(explode(' ', $source)));
        $partsCount = count($parts);
        if (0 !== $partsCount % 2) {
            throw new CleanerException("The schemaLocation value '" . $source . "' must have even number of URIs");
        }
        $modified = '';
        for ($k = 0; $k < $partsCount; $k = $k + 2) {
            if (! $this->isNameSpaceAllowed($parts[$k])) {
                continue;
            }
            $modified .= $parts[$k] . ' ' . $parts[$k + 1] . ' ';
        }
        $modified = rtrim($modified, ' ');
        if ($source == $modified) {
            return;
        }
        if ('' !== $modified) {
            $schemaLocation->nodeValue = $modified;
        } else {
            $schemaLocation->parentNode->attributes->removeNamedItemNS(
                $schemaLocation->namespaceURI,
                $schemaLocation->nodeName
            );
        }
    }

    /**
     * Procedure to remove all nodes that are not from an allowed namespace
     * @return void
     */
    public function removeNonSatNSNodes()
    {
        $nss = [];
        foreach ($this->xpathQuery('//namespace::*') as $node) {
            $namespace = $node->nodeValue;
            if ($this->isNameSpaceAllowed($namespace)) {
                continue;
            }
            $nss[] = $namespace;
        }
        if (! count($nss)) {
            return;
        }
        foreach ($nss as $namespace) {
            $this->removeNonSatNSNode($namespace);
        }
    }

    /**
     * Procedure to remove all nodes from an specific namespace
     * @param string $namespace
     * @return void
     */
    private function removeNonSatNSNode(string $namespace)
    {
        foreach ($this->dom()->getElementsByTagNameNS($namespace, '*') as $children) {
            $children->parentNode->removeChild($children);
        }
    }

    /**
     * Procedure to remove not allowed xmlns definitions
     * @return void
     */
    public function removeUnusedNamespaces()
    {
        $nss = [];
        $dom = $this->dom();
        foreach ($this->xpathQuery('//namespace::*') as $node) {
            $namespace = $node->nodeValue;
            if (! $namespace || $this->isNameSpaceAllowed($namespace)) {
                continue;
            }
            $prefix = $dom->lookupPrefix($namespace);
            $nss[$prefix] = $namespace;
        }
        $nss = array_unique($nss);
        foreach ($nss as $prefix => $namespace) {
            $dom->documentElement->removeAttributeNS($namespace, $prefix);
        }
    }

    /**
     * Helper function to perform a XPath query using an element (or root element)
     * @param string $query
     * @param DOMNode|null $element
     * @return DOMNodeList
     */
    private function xpathQuery(string $query, DOMNode $element = null): DOMNodeList
    {
        $element = $element ?: $this->dom()->documentElement;
        $nodelist = (new DOMXPath($element->ownerDocument))->query($query, $element);
        if (false === $nodelist) {
            $nodelist = new DOMNodeList();
        }
        return $nodelist;
    }

    private function dom(): DOMDocument
    {
        if (null === $this->dom) {
            throw new \LogicException('No document has been loaded');
        }
        return $this->dom;
    }
}
