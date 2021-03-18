<?php

namespace CfdiUtilsTests\Certificado;

use Exception;
use XmlResourceRetriever\Downloader\DownloaderInterface;
use XmlResourceRetriever\Downloader\PhpDownloader;

/**
 * This class is a wrapper around PhpDownloader to retry the download if it fails (for any reason).
 *
 * The reason behind this is that the web server at https://rdc.sat.gob.mx/
 * has issues and sometimes it does not respond with the certificate file.
 *
 * @see https://www.phpcfdi.com/sat/problemas-conocidos/descarga-certificados/
 */
final class CertificateDownloaderHelper implements DownloaderInterface
{
    /** @var PhpDownloader */
    private $realDownloader;

    private $maxAttempts;

    public function __construct()
    {
        $this->realDownloader = new PhpDownloader();
        $this->maxAttempts = 8;
    }

    public function downloadTo(string $source, string $destination)
    {
        $attempt = 1;
        while (true) {
            try {
                $this->realDownloader->downloadTo($source, $destination);
                break;
            } catch (Exception $exception) {
                // TODO: change to scpecific download exception when it exists
                if ($exception->getMessage() !== "Unable to download $source to $destination") {
                    throw $exception;
                }
                if ($attempt === $this->maxAttempts) {
                    throw $exception;
                }
                $attempt = $attempt + 1;
                continue;
            }
        }
    }
}
