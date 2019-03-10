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

    public function certificateIsPEM(string $contents): bool
    {
        return ('' !== $this->extractPEMContents($contents, 'CERTIFICATE'));
    }

    public function privateKeyIsPEM(string $contents): bool
    {
        return ('' !== $this->extractPEMContents($contents, 'PRIVATE KEY'))
            || ('' !== $this->extractPEMContents($contents, 'RSA PRIVATE KEY'))
            || ('' !== $this->extractPEMContents($contents, 'ENCRYPTED PRIVATE KEY'));
    }

    protected function convertToPEM(string $contents, string $type): string
    {
        $allowedTypes = ['CERTIFICATE', 'PRIVATE KEY'];
        if (! in_array($type, $allowedTypes, true)) {
            throw new \InvalidArgumentException(sprintf('Invalid type %s', $type));
        }
        return sprintf('-----BEGIN %s-----', $type) . PHP_EOL
            . chunk_split(base64_encode($contents), 64, PHP_EOL)
            . sprintf('-----END %s-----', $type) . PHP_EOL;
    }

    public function extractPEMContents(string $contents, string $type): string
    {
        $matches = [];
        $type = preg_quote($type, '/');
        // : , - are used un RSA PRIVATE KEYS
        $pattern = '/^-----BEGIN ' . $type . '-----[\sA-Za-z0-9+=\/:,-]+-----END ' . $type . '-----/m';
        preg_match($pattern, $contents, $matches);
        return strval($matches[0] ?? '');
    }

    public function convertCertificateToPEM(string $contents): string
    {
        return $this->convertToPEM($contents, 'CERTIFICATE');
    }

    public function whichOpenSSL(): string
    {
        $shellWhich = new ShellWhich();
        return $shellWhich->search('openssl');
    }

    public function convertPrivateKeyDERToPEM(string $contents, string $passphrase): string
    {
        $opensslPath = $this->getOpenSSLPath() ?: $this->whichOpenSSL();
        if ('' === $opensslPath) {
            throw new \RuntimeException('Cannot locate openssl executable');
        }

        $tempkey = TemporaryFile::create();
        file_put_contents($tempkey->getPath(), $contents);
        unset($contents);

        try {
            $command = sprintf(
                '%s pkcs8 -inform DER -passin env:PASSIN -in %s -out -',
                escapeshellarg($opensslPath),
                escapeshellarg($tempkey->getPath())
            );
            $execution = ShellExec::run($command, ['PASSIN' => $passphrase]);
        } finally {
            $tempkey->remove();
        }
        if ($execution->exitStatus() !== 0) {
            throw new \RuntimeException(
                sprintf('OpenSSL execution return with exit status of %d', $execution->exitStatus())
            );
        }

        return $execution->output();
    }

    public function protectPrivateKeyPEM(string $contents, $inPassPrase, string $outPassPhrase): string
    {
        // this error silenced call is intentional, avoid error and evaluate returned value
        $key = @openssl_pkey_get_private($contents, $inPassPrase);
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
}
