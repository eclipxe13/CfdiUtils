<?php

namespace CfdiUtils\CadenaOrigen;

use DOMDocument;
use Genkgo\Xsl\Cache\NullCache;
use Genkgo\Xsl\Exception\TransformationException;
use Genkgo\Xsl\XsltProcessor;

class GenkgoXslBuilder extends DOMBuilder
{
    public function __construct()
    {
        if (! class_exists(XsltProcessor::class)) {
            throw new \RuntimeException('To use GenkgoXslBuilder you must install genkgo/xsl'); // @codeCoverageIgnore
        }
    }

    protected function transform(DOMDocument $xml, DOMDocument $xsl): string
    {
        $xslt = new XSLTProcessor(new NullCache());
        $xslt->importStyleSheet($xsl);

        try {
            /** @var string|null|false $transform */
            $transform = $xslt->transformToXML($xml);
        } catch (TransformationException $exception) {
            throw new XsltBuildException('Error while transforming the xslt content', 0, $exception);
        }
        if (null === $transform || false === $transform) {
            throw $this->createLibXmlErrorOrMessage('Error while transforming the xslt content');
        }

        return $transform;
    }
}
