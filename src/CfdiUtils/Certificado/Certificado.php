<?php

namespace CfdiUtils\Certificado;

use CfdiUtils\OpenSSL\OpenSSL;
use CfdiUtils\OpenSSL\OpenSSLPropertyTrait;
use CfdiUtils\Utils\RegimenCapitalRemover;

class Certificado
{
    use OpenSSLPropertyTrait;

    private string $rfc;

    private string $certificateName;

    private string $name;

    private SerialNumber $serial;

    private int $validFrom;

    private int $validTo;

    private string $pubkey;

    private string $filename;

    private string $pemContents;

    private ?string $nameWithoutRegimenCapitalSuffix;

    /**
     * Certificado constructor.
     *
     * @param string $filename Allows filename or certificate contents (PEM or DER)
     * @throws \UnexpectedValueException when the certificate does not exist or is not readable
     * @throws \UnexpectedValueException when cannot read the certificate or is empty
     * @throws \RuntimeException when cannot parse the certificate or is empty
     * @throws \RuntimeException when cannot get serialNumberHex or serialNumber from certificate
     */
    public function __construct(string $filename, ?OpenSSL $openSSL = null)
    {
        $this->setOpenSSL($openSSL ?: new OpenSSL());
        $contents = $this->extractPemCertificate($filename);
        // using $filename as PEM content did not retrieve any result,
        // or the path actually exists (path is a valid base64 string)
        // then use it as path
        if ('' === $contents || realpath($filename)) {
            $sourceName = 'file ' . $filename;
            $this->assertFileExists($filename);
            $contents = file_get_contents($filename) ?: '';
            if ('' === $contents) {
                throw new \UnexpectedValueException("File $filename is empty");
            }
            // this will take PEM contents or perform a PHP conversion from DER to PEM
            $contents = $this->obtainPemCertificate($contents);
        } else {
            $filename = '';
            $sourceName = '(contents)';
        }

        // get the certificate data
        $data = openssl_x509_parse($contents, true);
        if (! is_array($data)) {
            throw new \RuntimeException("Cannot parse the certificate $sourceName");
        }

        // get the public key
        $pubKey = $this->obtainPubKeyFromContents($contents);

        // set all the values
        $this->certificateName = strval($data['name'] ?? '');
        $this->rfc = (string) strstr(($data['subject']['x500UniqueIdentifier'] ?? '') . ' ', ' ', true);
        $this->name = strval($data['subject']['name'] ?? '');
        $this->nameWithoutRegimenCapitalSuffix = null;
        $serial = new SerialNumber('');
        if (isset($data['serialNumberHex'])) {
            $serial->loadHexadecimal($data['serialNumberHex']);
        } elseif (isset($data['serialNumber'])) {
            $serial->loadDecimal($data['serialNumber']);
        } else {
            throw new \RuntimeException("Cannot get serialNumberHex or serialNumber from certificate $sourceName");
        }
        $this->serial = $serial;
        $this->validFrom = $data['validFrom_time_t'] ?? 0;
        $this->validTo = $data['validTo_time_t'] ?? 0;
        $this->pubkey = $pubKey;
        $this->pemContents = $contents;
        $this->filename = $filename;
    }

    private function extractPemCertificate(string $contents): string
    {
        $openssl = $this->getOpenSSL();
        $decoded = @base64_decode($contents, true) ?: '';
        if ('' !== $decoded && $contents === base64_encode($decoded)) { // is a one-liner certificate
            $doubleEncoded = $openssl->readPemContents($decoded)->certificate();
            if ('' !== $doubleEncoded) {
                return $doubleEncoded;
            }
            // derCerConvertPhp will include PEM header and footer
            $contents = $this->getOpenSSL()->derCerConvertPhp($decoded);
        }
        return $openssl->readPemContents($contents)->certificate();
    }

    private function obtainPemCertificate(string $contents): string
    {
        $openssl = $this->getOpenSSL();
        $extracted = $openssl->readPemContents($contents)->certificate();
        if ('' === $extracted) { // cannot extract, could be on DER format
            $extracted = $this->getOpenSSL()->derCerConvertPhp($contents);
        }
        return $extracted;
    }

    /**
     * Check if this certificate belongs to a private key
     *
     *
     *
     * @throws \UnexpectedValueException if the file does not exist or is not readable
     * @throws \UnexpectedValueException if the file is not a PEM private key
     * @throws \RuntimeException if the private key file cannot be opened
     */
    public function belongsTo(string $pemKeyFile, string $passPhrase = ''): bool
    {
        $this->assertFileExists($pemKeyFile);
        $openSSL = $this->getOpenSSL();
        $keyContents = $openSSL->readPemContents(
            // intentionally silence this error, if return false then cast it to string
            strval(@file_get_contents($pemKeyFile))
        )->privateKey();
        if ('' === $keyContents) {
            throw new \UnexpectedValueException("The file $pemKeyFile is not a PEM private key");
        }
        $privateKey = openssl_get_privatekey($keyContents, $passPhrase);
        if (false === $privateKey) {
            throw new \RuntimeException("Cannot open the private key file $pemKeyFile");
        }
        return openssl_x509_check_private_key($this->getPemContents(), $privateKey);
    }

    /**
     * RFC (Registro Federal de Contribuyentes) set when certificate was created
     */
    public function getRfc(): string
    {
        return $this->rfc;
    }

    /**
     * Certificate name value as returned by openssl.
     * In come cases (openssl version 3) it contains quoted slashes (\/)
     */
    public function getCertificateName(): string
    {
        return $this->certificateName;
    }

    /**
     * Name (Razón Social) set when certificate was created
     */
    public function getName($trimSuffix = false): string
    {
        return ($trimSuffix) ? $this->getNameWithoutRegimenCapitalSuffix() : $this->name;
    }

    /**
     * Name (Razón Social) set when certificate was created without *régimen de capital* suffix
     */
    public function getNameWithoutRegimenCapitalSuffix(): string
    {
        if (null === $this->nameWithoutRegimenCapitalSuffix) {
            $remover = RegimenCapitalRemover::createDefault();
            $this->nameWithoutRegimenCapitalSuffix = $remover->remove($this->name);
        }

        return $this->nameWithoutRegimenCapitalSuffix;
    }

    /**
     * Return the certificate serial number ASCII formatted, this data is in the format required by CFDI
     */
    public function getSerial(): string
    {
        return $this->serial->asAscii();
    }

    public function getSerialObject(): SerialNumber
    {
        return clone $this->serial;
    }

    /**
     * Timestamp since the certificate is valid
     */
    public function getValidFrom(): int
    {
        return $this->validFrom;
    }

    /**
     * Timestamp until the certificate is valid
     */
    public function getValidTo(): int
    {
        return $this->validTo;
    }

    /**
     * String representation of the public key
     */
    public function getPubkey(): string
    {
        return $this->pubkey;
    }

    /**
     * Place where the certificate was when loaded, it might not exist on the file system
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * The contents of the certificate in PEM format
     */
    public function getPemContents(): string
    {
        return $this->pemContents;
    }

    /**
     * The contents of the certificate in PEM format
     */
    public function getPemContentsOneLine(): string
    {
        return implode('', preg_grep('/^((?!-).)*$/', explode(PHP_EOL, $this->pemContents)));
    }

    /**
     * Verify the signature of some data
     *
     *
     *
     * @throws \RuntimeException if the public key on the certificate cannot be opened
     * @throws \RuntimeException if openssl report an error
     */
    public function verify(string $data, string $signature, int $algorithm = OPENSSL_ALGO_SHA256): bool
    {
        $pubKey = openssl_get_publickey($this->getPubkey());
        if (false === $pubKey) {
            throw new \RuntimeException('Cannot open public key from certificate');
        }
        $verify = openssl_verify($data, $signature, $pubKey, $algorithm);
        if (-1 === $verify) {
            throw new \RuntimeException('OpenSSL Error: ' . openssl_error_string());
        }
        return (1 === $verify);
    }

    /**
     * @throws \UnexpectedValueException when the file does not exist or is not readable
     */
    protected function assertFileExists(string $filename): void
    {
        $exists = false;
        $previous = null;
        try {
            if (preg_match('/[[:cntrl:]]/', $filename)) {
                $filename = '(invalid file name)';
                throw new \RuntimeException('The file name contains control characters, it might be a DER content');
            }
            if (file_exists($filename) && is_readable($filename) && ! is_dir($filename)) {
                $exists = true;
            }
        } catch (\Throwable $exception) {
            $previous = $exception;
        }
        if (! $exists) {
            $exceptionMessage = sprintf('File %s does not exists or is not readable', $filename);
            throw new \UnexpectedValueException($exceptionMessage, 0, $previous);
        }
    }

    protected function obtainPubKeyFromContents(string $contents): string
    {
        $pubkey = openssl_get_publickey($contents);
        if (false === $pubkey) {
            return '';
        }
        $pubData = openssl_pkey_get_details($pubkey) ?: [];
        return $pubData['key'] ?? '';
    }
}
