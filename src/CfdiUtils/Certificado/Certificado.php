<?php
namespace CfdiUtils\Certificado;

class Certificado
{
    /** @var string */
    private $rfc;
    /** @var string */
    private $name;
    /** @var string */
    private $serial;
    /** @var int */
    private $validFrom;
    /** @var int */
    private $validTo;
    /** @var string */
    private $pubkey;
    /** @var string */
    private $filename;

    /**
     * The contents of the certificate
     * @var string
     */
    private $pemContents;

    public function __construct(string $filename)
    {
        $this->assertFileExists($filename);
        // read contents, cast to string to avoid FALSE
        if ('' === $contents = (string) file_get_contents($filename)) {
            throw new \UnexpectedValueException("Cannot read the certificate file $filename or is empty");
        }

        // change to PEM format if it is not already
        if (0 !== strpos($contents, '-----BEGIN CERTIFICATE-----')) {
            $contents = $this->changeCerToPem($contents);
        }

        // get the certificate data
        $data = openssl_x509_parse($contents, true);
        if (! is_array($data)) {
            throw new \RuntimeException("Cannot parse the certificate file $filename");
        }

        // get the public key
        $pubkey = openssl_get_publickey($contents);
        try {
            $pubData = openssl_pkey_get_details($pubkey);
        } finally {
            openssl_free_key($pubkey);
        }

        // set all the values
        $this->rfc = (string) strstr($data['subject']['x500UniqueIdentifier'] . ' ', ' ', true);
        $this->name = $data['subject']['name'];
        $this->serial = $this->serialHexToAscii($data['serialNumberHex']);
        $this->validFrom = $data['validFrom_time_t'];
        $this->validTo = $data['validTo_time_t'];
        $this->pubkey = $pubData['key'];
        $this->pemContents = $contents;
        $this->filename = $filename;
    }

    /**
     * Check if this certificate belongs to a private key
     *
     * @param string $pemKeyFile
     * @param string $passPhrase
     *
     * @return bool
     *
     * @throws \UnexpectedValueException if the file does not exists or is not readable
     * @throws \UnexpectedValueException if the file is not a PEM private key
     * @throws \RuntimeException if cannot open the private key file
     */
    public function belongsTo(string $pemKeyFile, string $passPhrase = ''): bool
    {
        $this->assertFileExists($pemKeyFile);
        $keyContents = file_get_contents($pemKeyFile);
        if (0 !== strpos($keyContents, '-----BEGIN PRIVATE KEY-----')
            && 0 !== strpos($keyContents, '-----BEGIN RSA PRIVATE KEY-----')) {
            throw new \UnexpectedValueException("The file $pemKeyFile is not a PEM private key");
        }
        if (false === $privateKey = openssl_get_privatekey($keyContents, $passPhrase)) {
            throw new \RuntimeException("Cannot open the private key file $pemKeyFile");
        }
        $belongs = openssl_x509_check_private_key($this->getPemContents(), $privateKey);
        openssl_free_key($privateKey);
        return $belongs;
    }

    public function getRfc(): string
    {
        return $this->rfc;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSerial(): string
    {
        return $this->serial;
    }

    public function getValidFrom(): int
    {
        return $this->validFrom;
    }

    public function getValidTo(): int
    {
        return $this->validTo;
    }

    public function getPubkey(): string
    {
        return $this->pubkey;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getPemContents(): string
    {
        return $this->pemContents;
    }

    /**
     * Verify the signature of some data
     *
     * @param string $data
     * @param string $signature
     * @param int $algorithm
     *
     * @return bool
     *
     * @throws \RuntimeException if cannot open the public key from certificate
     * @throws \RuntimeException if openssl report an error
     */
    public function verify(string $data, string $signature, int $algorithm = OPENSSL_ALGO_SHA256): bool
    {
        if (false === $pubKey = openssl_get_publickey($this->getPubkey())) {
            throw new \RuntimeException('Cannot open public key from certificate');
        }
        try {
            $verify = openssl_verify($data, $signature, $pubKey, $algorithm);
            if (-1 === $verify) {
                throw new \RuntimeException('OpenSSL Error: ' . openssl_error_string());
            }
        } finally {
            openssl_free_key($pubKey);
        }
        return (1 === $verify);
    }

    /**
     * @param string $filename
     * @throws \UnexpectedValueException when the file does not exists or is not readable
     */
    protected function assertFileExists(string $filename)
    {
        if (! file_exists($filename) || ! is_readable($filename)) {
            throw new \UnexpectedValueException("File $filename does not exists or is not readable");
        }
    }

    protected function changeCerToPem(string $contents): string
    {
        return '-----BEGIN CERTIFICATE-----' . PHP_EOL
            . chunk_split(base64_encode($contents), 64, PHP_EOL)
            . '-----END CERTIFICATE-----' . PHP_EOL;
    }

    protected function serialHexToAscii(string $input): string
    {
        $ascii = '';
        $length = strlen($input);
        for ($i = 0; $i < $length; $i = $i + 2) {
            $ascii = $ascii . chr(hexdec(substr($input, $i, 2)));
        }
        return $ascii;
    }
}
