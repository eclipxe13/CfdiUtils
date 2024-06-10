<?php

namespace CfdiUtilsTests\Certificado;

use Exception;
use XmlResourceRetriever\Downloader\DownloaderInterface;

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
    const MAX_DOWNLOAD_ATTEMPTS = 8;

    public function downloadTo(string $source, string $destination)
    {
        $attempt = 1;
        while (true) {
            try {
                $this->realDownloadTo($source, $destination);
                break;
            } catch (Exception $exception) {
                if ($attempt === self::MAX_DOWNLOAD_ATTEMPTS) {
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
        curl_setopt($ch, CURLOPT_VERBOSE, 0); // set to 1 to debug
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
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
