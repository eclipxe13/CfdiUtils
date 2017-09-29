<?php
namespace CfdiUtils;

use DOMDocument;
use LibXMLError;
use XSLTProcessor;

/**
 * The class CadenaOrigen create the CadenaOrigen by transforming the XML contents
 * using the XSLT utilities provided by SAT
 */
class CadenaOrigen
{
    private $xsltLocations = [
        '3.2' => 'http://www.sat.gob.mx/sitio_internet/cfd/3/cadenaoriginal_3_2/cadenaoriginal_3_2.xslt',
        '3.3' => 'http://www.sat.gob.mx/sitio_internet/cfd/3/cadenaoriginal_3_3/cadenaoriginal_3_3.xslt',
    ];

    public function getXsltLocation(string $version): string
    {
        if (array_key_exists($version, $this->xsltLocations)) {
            return $this->xsltLocations[$version];
        }
        return '';
    }

    public function setXsltLocation(string $version, string $location)
    {
        $this->xsltLocations[$version] = $location;
    }

    /**
     * @return string[]
     */
    public function getXsltLocations(): array
    {
        return $this->xsltLocations;
    }

    public function build(string $cfdiContent, string $xsltLocation = ''): string
    {
        if ('' === $cfdiContent) {
            throw new \UnexpectedValueException('Content is empty');
        }
        $libxmlErrors = libxml_use_internal_errors(true);
        try {
            // load the cfdi document
            $cfdi = new DOMDocument();
            if (! $cfdi->loadXML($cfdiContent)) {
                $this->throwLibXmlErrorOrMessage('Error while loading the cfdi content');
            }

            // if not set, obtain default location from document version
            if ('' === $xsltLocation) {
                $cfdiVersion = (new Cfdi($cfdi))->getVersion();
                $xsltLocation = $this->getXsltLocation($cfdiVersion);
                if ('' === $xsltLocation) {
                    throw new \RuntimeException('Cannot get the CFDI version from the document');
                }
            }

            $xsl = new DOMDocument();
            if (! $xsl->load($xsltLocation)) {
                $this->throwLibXmlErrorOrMessage('Error while loading the Xslt location');
            }

            $xslt = new XSLTProcessor();
                $this->throwLibXmlErrorOrMessage('Error while importing the style sheet from the Xslt location');
            }

            // this error silenced call is intentional, avoid transformation errors except when return false
            if (false === $transform || null === $transform) {
                $this->throwLibXmlErrorOrMessage('Error while transforming the xslt content');
            }

            return $transform;
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($libxmlErrors);
        }
    }

    private function throwLibXmlErrorOrMessage(string $message)
    {
        $error = libxml_get_last_error();
        if (($error instanceof LibXMLError) && isset($error->message)) {
            $message = $message . ': ' . $error->message;
        }
        throw new \RuntimeException($message);
    }
}
