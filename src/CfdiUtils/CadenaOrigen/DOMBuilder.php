<?php

namespace CfdiUtils\CadenaOrigen;

use DOMDocument;
use LibXMLError;
use XSLTProcessor;

class DOMBuilder extends AbstractXsltBuilder
{
    public function build(string $xmlContent, string $xsltLocation): string
    {
        $this->assertBuildArguments($xmlContent, $xsltLocation);
        $libxmlErrors = libxml_use_internal_errors(true);
        try {
            // load the xml document
            $xml = new DOMDocument();
            if (! $xml->loadXML($xmlContent)) {
                throw $this->createLibXmlErrorOrMessage('Error while loading the xml content');
            }

            $xsl = new DOMDocument();
            if (! $xsl->load($xsltLocation)) {
                throw $this->createLibXmlErrorOrMessage('Error while loading the Xslt location');
            }

            return $this->transform($xml, $xsl);
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($libxmlErrors);
        }
    }

    protected function transform(DOMDocument $xml, DOMDocument $xsl): string
    {
        $xslt = new XSLTProcessor();
        if (! $xslt->importStylesheet($xsl)) {
            throw $this->createLibXmlErrorOrMessage('Error while importing the style sheet from the Xslt location');
        }

        // this error silenced call is intentional, avoid transformation errors except when return false
        /** @var string|null|false $transform */
        $transform = @$xslt->transformToXml($xml);
        if (null === $transform || false === $transform) {
            throw $this->createLibXmlErrorOrMessage('Error while transforming the xslt content');
        }

        return $transform;
    }

    protected function createLibXmlErrorOrMessage(string $message): XsltBuildException
    {
        $error = libxml_get_last_error();
        if (($error instanceof LibXMLError) && isset($error->message)) {
            $message = $message . ': ' . $error->message;
        }
        return new XsltBuildException($message);
    }
}
