<?php
namespace CfdiUtils\OpenSSL;

use CfdiUtils\Utils\Internal\ShellExec;
use CfdiUtils\Utils\Internal\TemporaryFile;

class OpenSSL
{
    private $openSSLPath;

    public function __construct(string $openSSLPath = '')
    {
        $this->openSSLPath = $openSSLPath;
    }

    public function getOpenSSLPath(): string
    {
        return $this->openSSLPath;
    }

    public function extractCertificate(string $contents)
    {
        return $this->extractPEMContents($contents, 'CERTIFICATE');
    }

    public function convertCertificateToPEM(string $contents): string
    {
        return '-----BEGIN CERTIFICATE-----' . PHP_EOL
            . chunk_split(base64_encode($contents), 64, PHP_EOL)
            . '-----END CERTIFICATE-----';
    }

    public function extractPrivateKey(string $contents)
    {
        foreach (['PRIVATE KEY', 'RSA PRIVATE KEY', 'ENCRYPTED PRIVATE KEY'] as $type) {
            $extracted = $this->extractPEMContents($contents, $type);
            if ('' !== $extracted) {
                return $extracted;
            }
        }
        return '';
    }

    public function convertPrivateKeyContentsDERToPEM(string $contents, string $passPhrase): string
    {
        $tempkey = TemporaryFile::create();
        file_put_contents($tempkey->getPath(), $contents);
        unset($contents);

        try {
            return $this->convertPrivateKeyFileDERToPEM($tempkey->getPath(), $passPhrase);
        } finally {
            $tempkey->remove();
        }
    }

    public function convertPrivateKeyFileDERToPEM(string $privateKeyPath, string $passPhrase): string
    {
        $tempfile = TemporaryFile::create();
        try {
            $this->convertPrivateKeyFileDERToFilePEM($privateKeyPath, $passPhrase, $tempfile->getPath());
            $output = strval(file_get_contents($tempfile->getPath()));
        } finally {
            $tempfile->remove();
        }

        if ('' === $output) {
            throw new \RuntimeException(sprintf('OpenSSL execution error. Cannot capture STDOUT'));
        }

        return $output;
    }

    public function convertPrivateKeyFileDERToFilePEM(
        string $privateKeyDerPath,
        string $passPhrase,
        string $privateKeyPemPath
    ) {
        $opensslPath = $this->getOpenSSLPath() ?: 'openssl';
        if ('' === $privateKeyDerPath) {
            throw new \RuntimeException('Private key in DER format (input) was not set');
        }
        if ('' === $privateKeyPemPath) {
            throw new \RuntimeException('Private key in PEM format (output) was not set');
        }
        if (file_exists($privateKeyPemPath) && filesize($privateKeyPemPath) > 0) {
            throw new \RuntimeException('Private key in PEM format (output) must not exists or be empty');
        }

        $command = [
            $opensslPath,
            'pkcs8',
            '-inform',
            'DER',
            '-passin',
            'env:PASSIN',
            '-in',
            $privateKeyDerPath,
            '-out',
            $privateKeyPemPath,
        ];
        $execution = ShellExec::run($command, ['PASSIN' => $passPhrase]);

        if ($execution->exitStatus() !== 0) {
            throw new \RuntimeException(
                sprintf('OpenSSL execution error. Exit status: %d', $execution->exitStatus())
            );
        }
    }

    public function protectPrivateKeyPEM(string $contents, string $inPassPhrase, string $outPassPhrase): string
    {
        $tempfile = TemporaryFile::create();
        try {
            file_put_contents($tempfile->getPath(), $contents);
            return $this->protectPrivateKeyPEMFileToPEM($tempfile->getPath(), $inPassPhrase, $outPassPhrase);
        } finally {
            $tempfile->remove();
        }
    }

    public function protectPrivateKeyPEMFileToPEM(
        string $pemInFile,
        string $inPassPhrase,
        string $outPassPhrase
    ): string {
        $tempfile = TemporaryFile::create();
        try {
            $this->protectPrivateKeyPEMFileToPEMFile($pemInFile, $inPassPhrase, $tempfile->getPath(), $outPassPhrase);
            $output = strval(file_get_contents($tempfile->getPath()));
        } finally {
            $tempfile->remove();
        }

        if ('' === $output) {
            throw new \RuntimeException(sprintf('OpenSSL execution error. Cannot capture STDOUT'));
        }

        return $output;
    }

    public function protectPrivateKeyPEMFileToPEMFile(
        string $pemInFile,
        string $inPassPhrase,
        string $pemOutFile,
        string $outPassPhrase
    ) {
        if ('' === $pemInFile) {
            throw new \RuntimeException('Private key in PEM format (input) was not set');
        }
        if ('' === $pemOutFile) {
            throw new \RuntimeException('Private key in PEM format (output) was not set');
        }
        if (file_exists($pemOutFile) && filesize($pemOutFile) > 0) {
            throw new \RuntimeException('Private key in PEM format (output) must not exists or be empty');
        }

        $command = [
            $this->getOpenSSLPath() ?: 'openssl',
            'rsa',
            '-in',
            $pemInFile,
            '-passin',
            'env:PASSIN',
            '-des3',
            '-out',
            $pemOutFile,
            '-passout',
            'env:PASSOUT',
        ];
        $execution = ShellExec::run($command, ['PASSIN' => $inPassPhrase, 'PASSOUT' => $outPassPhrase]);

        if ($execution->exitStatus() !== 0) {
            throw new \RuntimeException(
                sprintf('OpenSSL execution error. Exit status: %d', $execution->exitStatus())
            );
        }
    }

    private function extractPEMContents(string $contents, string $type): string
    {
        $matches = [];
        $type = preg_quote($type, '/');
        // : , - are used un RSA PRIVATE KEYS
        $pattern = '/^-----BEGIN ' . $type . '-----[\sA-Za-z0-9+=\/:,-]+-----END ' . $type . '-----/m';
        preg_match($pattern, $contents, $matches);
        return strval($matches[0] ?? '');
    }
}
