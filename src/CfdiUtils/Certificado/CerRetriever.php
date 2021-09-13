<?php

namespace CfdiUtils\Certificado;

use Eclipxe\XmlResourceRetriever\AbstractBaseRetriever;
use Eclipxe\XmlResourceRetriever\RetrieverInterface;
use PhpCfdi\Credentials\Certificate;

class CerRetriever extends AbstractBaseRetriever implements RetrieverInterface
{
    public function retrieve(string $url): string
    {
        $this->clearHistory();
        $localFilename = $this->download($url);
        $this->addToHistory($url, $localFilename);
        return $localFilename;
    }

    protected function checkIsValidDownloadedFile(string $source, string $localpath): void
    {
        // check content is cer file
        try {
            Certificate::openFile($localpath);
        } catch (\Throwable $ex) {
            unlink($localpath);
            throw new \RuntimeException("The source $source is not a cer file", 0, $ex);
        }
    }
}
