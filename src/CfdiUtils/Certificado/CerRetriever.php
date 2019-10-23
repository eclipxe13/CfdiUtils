<?php

namespace CfdiUtils\Certificado;

use XmlResourceRetriever\AbstractBaseRetriever;
use XmlResourceRetriever\RetrieverInterface;

class CerRetriever extends AbstractBaseRetriever implements RetrieverInterface
{
    public function retrieve(string $resource): string
    {
        $this->clearHistory();
        $localFilename = $this->download($resource);
        $this->addToHistory($resource, $localFilename);
        return $localFilename;
    }

    protected function checkIsValidDownloadedFile(string $source, string $localpath)
    {
        // check content is cer file
        try {
            new Certificado($localpath);
        } catch (\Throwable $ex) {
            unlink($localpath);
            throw new \RuntimeException("The source $source is not a cer file", 0, $ex);
        }
    }
}
