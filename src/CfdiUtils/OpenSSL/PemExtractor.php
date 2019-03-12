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

    public function pemContainer(): PemContainer
    {
        return new PemContainer(
            $this->extractCertificate(),
            $this->extractPublicKey(),
            $this->extractPrivateKey()
        );
    }

    public function extractCertificate(): string
    {
        return $this->extractFirst(['CERTIFICATE']);
    }

    public function extractPublicKey(): string
    {
        return $this->extractFirst(['PUBLIC KEY']);
    }

    public function extractPrivateKey(): string
    {
        return $this->extractFirst(['PRIVATE KEY', 'RSA PRIVATE KEY', 'ENCRYPTED PRIVATE KEY']);
    }

    protected function extractFirst(array $types): string
    {
        foreach ($types as $type) {
            $extracted = $this->extract($type);
            if ('' !== $extracted) {
                return $extracted;
            }
        }
        return '';
    }

    protected function extract(string $type): string
    {
        $matches = [];
        $type = preg_quote($type, '/');
        // : , - are used un RSA PRIVATE KEYS
        $pattern = '/^-----BEGIN ' . $type . '-----[\sA-Za-z0-9+=\/:,-]+-----END ' . $type . '-----/m';
        preg_match($pattern, $this->getContents(), $matches);
        return $this->fixLineEndings(strval($matches[0] ?? ''));
    }

    protected function fixLineEndings(string $content): string
    {
        return preg_replace('/\v/', PHP_EOL, $content) ?: '';
    }
}
