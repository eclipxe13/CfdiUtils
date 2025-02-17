<?php

namespace CfdiUtilsTests\Certificado;

use Eclipxe\XmlResourceRetriever\Downloader\DownloaderInterface;
use Exception;

/**
 * This class is a wrapper around PhpDownloader to retry the download if it fails (for any reason).
 *
 * The reason behind this is that the web server at https://rdc.sat.gob.mx/
 * has issues, and sometimes it does not respond with the certificate file.
 *
 * @see https://www.phpcfdi.com/sat/problemas-conocidos/descarga-certificados/
 */
final class CertificateDownloaderHelper implements DownloaderInterface
{
    public const MAX_DOWNLOAD_ATTEMPTS = 8;

    public function downloadTo(string $source, string $destination)
    {
        $attempt = 1;
        while (true) {
            try {
                $this->realDownloadTo($source, $destination);
                break;
            } catch (Exception $exception) {
                if (self::MAX_DOWNLOAD_ATTEMPTS === $attempt) {
                    throw new Exception("Unable to download $source to $destination", 0, $exception);
                }
                $attempt = $attempt + 1;
                continue;
            }
        }
    }

    private function realDownloadTo(string $source, string $destination)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $source);
        curl_setopt($ch, CURLOPT_VERBOSE, false); // set to true to debug
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $result = (string) curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);

        if ('' === $result) {
            throw new Exception('Response is empty');
        }
        if (! is_scalar($status)) {
            throw new Exception('Invalid status code');
        }
        $status = (int) $status;
        if (200 !== $status) {
            throw new Exception('Status code is not 200');
        }

        if (false === @file_put_contents($destination, $result)) {
            throw new Exception('Cannot save certificate on destination');
        }
    }
}
