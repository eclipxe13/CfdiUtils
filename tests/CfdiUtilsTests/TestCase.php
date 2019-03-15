<?php
namespace CfdiUtilsTests;

use CfdiUtils\Certificado\SatCertificateNumber;
use CfdiUtils\XmlResolver\XmlResolver;
use XmlResourceRetriever\Downloader\DownloaderInterface;
use XmlResourceRetriever\Downloader\PhpDownloader;

class TestCase extends \PHPUnit\Framework\TestCase
{
    public static function utilAsset(string $file)
    {
        return dirname(__DIR__) . '/assets/' . $file;
    }

    protected function isRunningOnWindows(): bool
    {
        return ('\\' === DIRECTORY_SEPARATOR);
    }

    protected function newInsecurePhpDownloader(): DownloaderInterface
    {
        // disable ssl verification connecting to https://rdc.sat.gob.mx/ since SAT web server has config errors
        return new PhpDownloader(
            stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                ],
            ])
        );
    }

    protected function newResolver(DownloaderInterface $downloader = null)
    {
        $xmlResolver = new XmlResolver();
        if (null !== $downloader) {
            $xmlResolver->setDownloader($downloader);
        }
        return $xmlResolver;
    }

    protected function downloadResourceIfNotExists(string $remote): string
    {
        return $this->newResolver()->resolve($remote);
    }

    public function providerFullJoin(array $first, array ...$next): array
    {
        if (! count($next)) {
            return $first;
        }
        $combine = [];
        $second = array_shift($next);
        foreach ($first as $a) {
            foreach ($second as $b) {
                $combine[] = array_merge($a, $b);
            }
        }
        if (count($next)) {
            return $this->providerFullJoin($combine, ...$next);
        }
        return $combine;
    }

    protected function installCertificate(string $cerfile): string
    {
        $certificateNumber = substr(basename($cerfile), 0, 20);
        $satCertificateNumber = new SatCertificateNumber($certificateNumber);

        $cerRetriever = $this->newResolver()->newCerRetriever();

        $installationPath = $cerRetriever->buildPath($satCertificateNumber->remoteUrl());
        if (file_exists($installationPath)) {
            return $installationPath;
        }

        $installationDir = dirname($installationPath);
        if (! file_exists($installationDir)) {
            mkdir($installationDir, 0774, true);
        }
        if (! is_dir($installationDir)) {
            throw new \RuntimeException("Cannot create installation dir $installationDir");
        }

        if (! copy($cerfile, $installationPath)) {
            throw new \RuntimeException("Cannot install $cerfile into $installationPath");
        }

        return $installationPath;
    }
}
