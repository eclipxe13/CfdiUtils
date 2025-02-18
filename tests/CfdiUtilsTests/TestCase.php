<?php

namespace CfdiUtilsTests;

use CfdiUtils\Certificado\Certificado;
use CfdiUtils\Certificado\SatCertificateNumber;
use CfdiUtils\XmlResolver\XmlResolver;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    public static function captureException(callable $function): ?\Throwable
    {
        try {
            call_user_func($function);
            return null;
        } catch (\Throwable $exception) {
            return $exception;
        }
    }

    public static function utilAsset(string $file): string
    {
        return dirname(__DIR__) . '/assets/' . $file;
    }

    protected function isRunningOnWindows(): bool
    {
        return ('\\' === DIRECTORY_SEPARATOR);
    }

    protected function newResolver(): XmlResolver
    {
        return new XmlResolver();
    }

    protected function downloadResourceIfNotExists(string $remote): string
    {
        return $this->newResolver()->resolve($remote);
    }

    public function providerFullJoin(array $first, array ...$next): array
    {
        if ($next === []) {
            return $first;
        }
        $combine = [];
        $second = array_shift($next) ?: [];
        foreach ($first as $a) {
            foreach ($second as $b) {
                $combine[] = array_merge($a, $b);
            }
        }
        if ($next !== []) {
            return $this->providerFullJoin($combine, ...$next);
        }
        return $combine;
    }

    protected function installCertificate(string $cerfile): string
    {
        $resolver = $this->newResolver();

        $certificate = new Certificado('file://' . $cerfile);
        $certificateNumber = $certificate->getSerial();
        $satCertificateNumber = new SatCertificateNumber($certificateNumber);

        $cerRetriever = $resolver->newCerRetriever();
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
