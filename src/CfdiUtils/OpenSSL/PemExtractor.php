<?php
namespace CfdiUtils\OpenSSL;

class PemExtractor
{
    /** @var string */
    private $contents;

    public function __construct($contents)
    {
        $this->contents = $contents;
    }

    public function getContents(): string
    {
        return $this->contents;
    }

    public function extractCertificate(): string
    {
        return $this->extractBase64('CERTIFICATE');
    }

    public function extractPublicKey(): string
    {
        return $this->extractBase64('PUBLIC KEY');
    }

    public function extractPrivateKey(): string
    {
        if ('' !== $extracted = $this->extractBase64('PRIVATE KEY')) {
            return $extracted;
        }
        if ('' !== $extracted = $this->extractBase64('RSA PRIVATE KEY')) {
            return $extracted;
        }
        if ('' !== $extracted = $this->extractRsaProtected()) {
            return $extracted;
        }
        return $this->extractBase64('ENCRYPTED PRIVATE KEY');
    }

    protected function extractBase64(string $type): string
    {
        $matches = [];
        $type = preg_quote($type, '/');
        $pattern = '/^'
            . '-----BEGIN ' . $type . '-----\r?\n'
            . '([A-Za-z0-9+\/=]+\r?\n)+'
            . '-----END ' . $type . '-----\r?'
            . '$/m';
        preg_match($pattern, $this->getContents(), $matches);
        return $this->fixLineEndings(strval($matches[0] ?? ''));
    }

    protected function extractRsaProtected(): string
    {
        $matches = [];
        $pattern = '/^'
            . '-----BEGIN RSA PRIVATE KEY-----\r?\n'
            . 'Proc-Type: .+\r?\n'
            . 'DEK-Info: .+\r?\n\r?\n'
            . '([A-Za-z0-9+\/=]+\r?\n)+'
            . '-----END RSA PRIVATE KEY-----\r?'
            . '$/m';
        preg_match($pattern, $this->getContents(), $matches);
        return $this->fixLineEndings(strval($matches[0] ?? ''));
    }

    protected function fixLineEndings(string $content): string
    {
        // move '\r\n' or '\n' to PHP_EOL
        // first substitution '\r' -> ''
        // second substitution '\n' -> PHP_EOL
        return str_replace(["\r", "\n"], ['', PHP_EOL], $content);
    }
}
