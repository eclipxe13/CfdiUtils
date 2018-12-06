<?php
namespace CfdiUtils\Certificado;

class Certificado
{
    /** @var string */
    private $rfc;

    /** @var string */
    private $certificateName;

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

    /** @var string */
    private $pemContents;

    /**
     * Certificado constructor.
     *
     * @param string $filename
     * @throws \UnexpectedValueException when the file does not exists or is not readable
     * @throws \UnexpectedValueException when cannot read the certificate file or is empty
     * @throws \RuntimeException when cannot parse the certificate file or is empty
     * @throws \RuntimeException when cannot get serialNumberHex or serialNumber from certificate
     */
    public function __construct(string $filename)
    {
        $this->assertFileExists($filename);
        // read contents, cast to string to avoid FALSE
        if ('' === $contents = (string) file_get_contents($filename)) {
            throw new \UnexpectedValueException("File $filename is empty");
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
        if (! isset($data['subject'])) {
            $data['subject'] = [];
        }

        // get the public key
        $pubKey = $this->obtainPubKeyFromContents($contents);

        // set all the values
        $this->certificateName = strval($data['name'] ?? '');
        $this->rfc = (string) strstr(($data['subject']['x500UniqueIdentifier'] ?? '') . ' ', ' ', true);
        $this->name = strval($data['subject']['name'] ?? '');
        $serial = new SerialNumber('');
        if (isset($data['serialNumberHex'])) {
            $serial->loadHexadecimal($data['serialNumberHex']);
        } elseif (isset($data['serialNumber'])) {
            $serial->loadDecimal($data['serialNumber']);
        } else {
            throw new \RuntimeException("Cannot get serialNumberHex or serialNumber from certificate file $filename");
        }
        $this->serial = $serial->asAscii();
        $this->validFrom = $data['validFrom_time_t'] ?? 0;
        $this->validTo = $data['validTo_time_t'] ?? 0;
        $this->pubkey = $pubKey;
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
        // intentionally silence this error, if return false cast to string
        $keyContents = (string) @file_get_contents($pemKeyFile);
        if (0 !== strpos($keyContents, '-----BEGIN PRIVATE KEY-----')
            && 0 !== strpos($keyContents, '-----BEGIN RSA PRIVATE KEY-----')) {
            throw new \UnexpectedValueException("The file $pemKeyFile is not a PEM private key");
        }
        $privateKey = openssl_get_privatekey($keyContents, $passPhrase);
        if (false === $privateKey) {
            throw new \RuntimeException("Cannot open the private key file $pemKeyFile");
        }
        $belongs = openssl_x509_check_private_key($this->getPemContents(), $privateKey);
        openssl_free_key($privateKey);
        return $belongs;
    }

    /**
     * RFC (Registro Federal de Contribuyentes) set when certificate was created
     * @return string
     */
    public function getRfc(): string
    {
        return $this->rfc;
    }

    public function getCertificateName(): string
    {
        return $this->certificateName;
    }

    /**
     * Name (Razón Social) set when certificate was created
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Certificate serial number as ASCII, this data is in the format required by CFDI
     * @return string
     */
    public function getSerial(): string
    {
        return $this->serial;
    }

    /**
     * Timestamp since the certificate is valid
     * @return int
     */
    public function getValidFrom(): int
    {
        return $this->validFrom;
    }

    /**
     * Timestamp until the certificate is valid
     * @return int
     */
    public function getValidTo(): int
    {
        return $this->validTo;
    }

    /**
     * String representation of the public key
     * @return string
     */
    public function getPubkey(): string
    {
        return $this->pubkey;
    }

    /**
     * Place where the certificate was when loaded, it might not exists on the file system
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * The contents of the certificate in PEM format
     * @return string
     */
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
        $pubKey = openssl_get_publickey($this->getPubkey());
        if (false === $pubKey) {
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
     * @return void
     */
    protected function assertFileExists(string $filename)
    {
        if (! file_exists($filename) || ! is_readable($filename) || is_dir($filename)) {
            throw new \UnexpectedValueException("File $filename does not exists or is not readable");
        }
    }

    protected function changeCerToPem(string $contents): string
    {
        return '-----BEGIN CERTIFICATE-----' . PHP_EOL
            . chunk_split(base64_encode($contents), 64, PHP_EOL)
            . '-----END CERTIFICATE-----' . PHP_EOL;
    }

    protected function obtainPubKeyFromContents(string $contents): string
    {
        try {
            $pubkey = openssl_get_publickey($contents);
            if (! is_resource($pubkey)) {
                return '';
            }
            $pubData = openssl_pkey_get_details($pubkey) ?: [];
            return $pubData['key'] ?? '';
        } finally {
            // close public key even if the flow is throw an exception
            if (isset($pubkey) && is_resource($pubkey)) {
                openssl_free_key($pubkey);
            }
        }
    }
}
