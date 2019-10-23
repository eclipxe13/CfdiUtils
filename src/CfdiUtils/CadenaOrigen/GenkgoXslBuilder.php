<?php

namespace CfdiUtils\CadenaOrigen;

use DOMDocument;
use Genkgo\Xsl\XsltProcessor;

class GenkgoXslBuilder extends DOMBuilder
{
    public function __construct()
    {
        if (! class_exists(XsltProcessor::class)) {
            throw new \RuntimeException('To use GenkgoXslBuilder you must install genkgo/xsl');
        }
    }

    protected function transform(DOMDocument $xml, DOMDocument $xsl): string
    {
        $xslt = new XSLTProcessor();
        $xslt->importStyleSheet($xsl);

        // this error silenced call is intentional, avoid transformation errors except when return false
        /** @var string|null|false $transform */
        $transform = @$xslt->transformToXML($xml);
        if (null === $transform || false === $transform) {
            throw $this->createLibXmlErrorOrMessage('Error while transforming the xslt content');
        }

        return $transform;
    }
}
