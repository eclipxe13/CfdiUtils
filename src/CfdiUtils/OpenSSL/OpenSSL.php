<?php
namespace CfdiUtils\OpenSSL;

use CfdiUtils\Utils\Internal\ShellExec;
use CfdiUtils\Utils\Internal\ShellWhich;
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

    public function convertPrivateKeyFileDERToPEM(string $privateKeyPath, string $passPhrase): string
    {
        $opensslPath = $this->getOpenSSLPath() ?: $this->whichOpenSSL();
        if ('' === $opensslPath) {
            throw new \RuntimeException('Cannot locate openssl executable');
        }

        $command = sprintf(
            '%s pkcs8 -inform DER -passin env:PASSIN -in %s -out -',
            escapeshellarg($opensslPath),
            escapeshellarg($privateKeyPath)
        );
        $execution = ShellExec::run($command, ['PASSIN' => $passPhrase]);
        if ($execution->exitStatus() !== 0) {
            throw new \RuntimeException(
                sprintf('OpenSSL execution error. Exit status: %d', $execution->exitStatus())
            );
        }

        return $execution->output();
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

    public function protectPrivateKeyPEM(string $contents, string $inPassPhrase, string $outPassPhrase): string
    {
        if ($inPassPhrase === $outPassPhrase) {
            throw new \RuntimeException('The current pass phrase and the new pass phrase are the same');
        }

        // this error silenced call is intentional, avoid error and evaluate returned value
        $key = @openssl_pkey_get_private($contents, $inPassPhrase);
        if (! is_resource($key)) {
            throw new \RuntimeException('Unable to open private key');
        }

        $exported = '';
        try {
            $exportcall = openssl_pkey_export($key, $exported, $outPassPhrase, [
                'private_key_type' => OPENSSL_KEYTYPE_RSA,
                'encrypt_key_cipher' => OPENSSL_CIPHER_3DES,
            ]);
            if (! $exportcall) {
                throw new \RuntimeException('Unable to export private key');
            }
        } finally {
            openssl_free_key($key);
        }

        if ('' === $exported) {
            throw new \RuntimeException('Unable to export private key');
        }

        return $exported;
    }

    protected function whichOpenSSL(): string
    {
        $shellWhich = new ShellWhich();
        return $shellWhich->search('openssl');
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
